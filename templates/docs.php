<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="0bs.de - Documentation Guide">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Documentation - 0bs.de</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <style>
          .table thead {
            color: #aaa;
          }
          .master-row {
            cursor: pointer;
          }
          .master-row .label {
            font-size: 16px;
          }
          .warning {
            font-weight: bold;
          }
          pre {
            padding: 0;
          }
          .hljs {
            background: transparent !important;
          }
        </style>
    </head>
    <body>
      <div class="container">
        <h1>0bs.de - API Reference</h1>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Links</h3>
          </div>
          <div class="panel-body">
         <p><strong>INFORMATION: There is a rate-limit, so just keep calm a bit.</strong></p>
          
        <table class="table">
          <tbody>
            <tr class="master-row info">
              <td><span class="label label-primary">GET</span></td>
              <td><b>/api/getLink/<span class="text-muted">{id}</span></b></td>
              <td>Returns all informations about the given link</td>
            </tr>
            <tr>
              <td colspan="3">
                <h4>Parameters</h4>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Parameter name</th>
                      <th>Value</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="warning">
                      <td>id</td>
                      <td>\w+</td>
                      <td>ID parameter</td>
                    </tr>
                  </tbody>
                </table>
                <h4>Usage</h4>

                <div class="pull-left" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X GET 'http://0bs.de/api/getLink/aN'</code></pre>
                <h5>Output</h5>
<pre>
<code>
{
  "id": "aN",
  "urlShortened": "http://example.com",
  "createDate": "2014-07-01 21:56:10",
  "expireDate": null,
  "fullURL": "http://0bs.de/aN",
  "error": false,
  "status": 200
}</code>
</pre>
</div>
<div class="pull-right" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X GET 'http://0bs.de/api/getLink/eeeeeee'</code></pre>
                <h5>Output</h5>
<pre>
<code>
{
  "error": true,
  "msg": "Link has not been found",
  "status": 404
}
</code>
</pre>
</div>
              </td>
            </tr>
            <tr class="master-row info">
              <td><span class="label label-primary">GET</span></td>
              <td><b>/api/getQrCode/<span class="text-muted">{id}</span></b></td>
              <td>Returns the QR-Code Image for the given link</td>
            </tr>
            <tr>
              <td colspan="3">
                <h4>Parameters</h4>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Parameter name</th>
                      <th>Value</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="warning">
                      <td>id</td>
                      <td>\w+</td>
                      <td>ID parameter</td>
                    </tr>
                  </tbody>
                </table>
                <h4>Usage</h4>

                <div class="pull-left" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X GET 'http://0bs.de/api/getQrCode/aN'</code></pre>
                <h5>Output</h5>
                <img src="http://0bs.de/api/getQrCode/aN" alt="qr-code-example"><br />
                <strong>Data for PNG-image</strong> is returned, added in here with img-tag.
                </div>
<div class="pull-right" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X GET 'http://0bs.de/api/getQrCode/eeeeeee'</code></pre>
                <h5>Output</h5>
<pre>
<code>
{
  "error": true,
  "msg": "Link has not been found",
  "status": 404
}
</code>
</pre>
</div>
              </td>
            </tr>

            <tr class="master-row success">
              <td><span class="label label-success">PUT</span></td>
              <td><b>/api/addLink</b></td>
              <td>Saves the given link and creates a shortened URL</td>
            </tr>
            <tr>
              <td colspan="3">
                <h4>Parameters</h4>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Parameter name</th>
                      <th>Value</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="warning">
                      <td>url</td>
                      <td>\s+</td>
                      <td>Valid URL in following format: http://subdomain.domain.com</td>
                    </tr>
                  </tbody>
                </table>
                <h4>Usage</h4>

                <div class="pull-left" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X PUT 'http://0bs.de/api/addLink' -d 'url=http://subdomain.domain.com'</code></pre>
                <h5>Output</h5>
<pre>
<code>
{
  "id": "bsp",
  "urlShortened": "http://subdomain.domain.com/",
  "createDate": "2014-07-01 22:33:54",
  "expireDate": null,
  "fullURL": "http://0bs.de/bsp", 
  "error": false,
  "status": 201
}
</code>
</pre>
Status: <b>201</b> - new link created, <b>200</b> existing link returned
</div>
<div class="pull-right" style="width: 49%">
                <h5>Request</h5>
                <pre><code>curl -X PUT 'http://0bs.de/api/addLink' -d 'url=notlink'</code></pre>
                <h5>Output</h5>
<pre>
<code>
{
  "error": true,
  "msg": "Provided url is not in a valid form",
  "status": 400
}
</code>
</pre>
</div>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
</div>
      </div>
      <link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/atelier-forest.light.min.css">
      <script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
      <script>
        hljs.initHighlightingOnLoad();
      </script>
    </body>
</html>
