<?php
use Endroid\QrCode\QrCode;

class API {
  
  protected $db;
  private $baseUrl;

  /**
   * Database connection
   * 
   * @param StdClass $options Contains hostname, dbname, user, password for the db connection
   */
  public function __construct($options, $baseUrl = 'ur2.pl') {
    $this->db      = new PDO("mysql:host=$options->hostname;dbname=$options->database", $options->user, $options->password);
    $this->baseUrl = $baseUrl;
  }

  /**
   * Returns db row if url with given ID exists
   * 
   * @param  Int        $id ID of the url
   * @return Array/Null     Array of data or null if not found
   */
  public function getLinkById($id) {
    $result = $this->db->query("SELECT * FROM `links` WHERE `id` = ".self::base58_decode($id))->fetch(PDO::FETCH_ASSOC);
    if ($result != null) {
      return $this->prepareResult($result);
    }
    return $result;
  }

  /**
   * Returns db row if given url exists in the db
   * 
   * @param  String     $url String containing url
   * @return Array/Null      Array of data or null if not found
   */
  public function getLinkByUrl($url) {
    $result = $this->db->query("SELECT * FROM `links` WHERE `url_shortened` = '$url'")->fetch(PDO::FETCH_ASSOC);
    if ($result != null) {
      return $this->prepareResult($result);
    }
    return $result;
  }

  /**
   * Adds url to the database (without checking if exists)
   * 
   * @param String $url String containing url
   * @return Int Last insert ID of the row
   */
  public function addLink($url) {
    $insertRequest = $this->db->query("INSERT INTO `links` VALUES (null, '".$url."', now(), null)");
    if($insertRequest) {
        $result = array('id' => (int)$this->db->lastInsertId(),
                        'url_shortened' => $url,
                        'create_date' => date("Y-m-d H:i:s"),
                        'expire_date' => null);
        return $this->prepareResult($result);
    }
  }


  /**
   * prepare the results array correctly to be returned
   *
   * @param Array  $result   Results from db/from generation
   * @return Array $result   Patched array for output
   *
  */
  private function prepareResult($result) {
    $result['id'] = self::base58_encode($result['id']);
    $result['full_url'] = 'https://'.$this->baseUrl.'/'.$result['id'];
    return self::camelizeKeys($result);
  }

  /**
   * Validates given url
   * 
   * @param  String   $url String containing url
   * @return Boolean       True if valid URL, false if not
   */
  public function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
  }


  /**
   * Camelize all array keys
   * 
   * @param  Array $array Given array
   * @return Array        Array with camelized keys
   */
  private static function camelizeKeys($array) {
    $outputArray = array();

    foreach ($array as $key => $value) {
      $outputArray[self::camelize($key)] = $value;
    }

    return $outputArray;
  }

  /**
   * Camelize given string
   * 
   * @param  String  $value   Input string
   * @param  boolean $lcfirst Describes if the first letter should be lowercase, default true
   * @return String           Camelized string
   *
  */
  private static function camelize($value, $lcfirst = true) {
    $value = implode('', array_map('ucfirst', array_map('strtolower', explode('_', $value))));
    return ($lcfirst === TRUE ? lcfirst($value) : $value);
  }

  /**
   * Encode given integer to base58
   *
   * @param  Integer  $n      Input integer
   * @return String           Base58 encoded string
   *
  */
  private static function base58_encode($n) {
    $alphabet = str_split("123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ");

    if($n == 0) return $alphabet[0];

    $result = "";
    $base = sizeof($alphabet);

    while($n > 0) {
      $reminder = $n % $base;
      $n = (int)($n / $base);
      $result = $alphabet[$reminder].$result;
    }

    return $result;
  }

  /**
   * Decode given base58 string
   *
   * @param  String  $n      Input string
   * @return Integer         decoded self::base58 string
   *
  */
  private static function base58_decode($n) {
    $alphabet = str_split("123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ");

    $decoded = 0;
    $multi = 1;

    $i = strlen($n) - 1;
    while($i >= 0) {
      $decoded = $decoded + $multi * array_search(substr($n, $i, 1), $alphabet);
      $multi = $multi * sizeof($alphabet);
      $i = $i - 1;
    }

    return $decoded;
  }

  /**
   * Generate QR-Code by given link id
   *
   * @param String $id      Link id
   * @return Object         QrCode Object
   *
  */
  public function qrCode($id) {
    $result = $this->getLinkById($id);
    if($result) {
      $qrCode = new QrCode();
      $qrCode
        ->setText($result['fullUrl'])
        ->setSize(150)
        ->setPadding(5)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));

      return $qrCode;
    }
  }
}
