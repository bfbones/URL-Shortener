#!/usr/bin/env ruby

unless ARGV[0]
  puts "Argument url is required! (e.g.: short.rb http://example.com)"
  exit
end

require 'net/http'
require 'json'

url = ARGV[0]
api = URI.parse("https://0bs.de/api/addLink")
port = 443

http = Net::HTTP.new(api.hostname, api.port)
http.use_ssl = true

request = Net::HTTP::Put.new(api.path)
request.set_form_data({"url" => url})
response = http.request(request)

if response and (response.code.to_i == 200 or response.code.to_i == 201)
  data = JSON::parse(response.body)
  puts data['fullUrl']
  puts "QR: https://0bs.de/api/getQrCode/#{data['id']}" if ARGV[1] and ARGV[1] == "-qr"
else
  puts "ERROR (#{response.code}): #{response.message}"
end
