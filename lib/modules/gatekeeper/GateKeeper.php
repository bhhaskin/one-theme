<?php
class GateKeeperCaptcha {

  public $width = null;
  public $height = null;
  public $fontSize = 22;
  public $font = "fonts/Vera.ttf";
  public $backgroundColor = array(51,84,87);
  public $foregroundColor = array(191,225,225);
  public $lineColor = array(52,92,100);
  public $textColor = array(242,226,179);
  public $length = 4;
  public $output = 'png';
  public $letterRotation = array(-5, 5);
  public $lines = array(1, 5);
  public $dots = array(400, 2000);
  public $challengeFunct;
  public $challenge;
  public $answer;
  public $session = "gatekeeper";


  function __construct($data = array()) {
    $this->challengeFunct = $this->mathChallenge();

    if(count($data) > 0) {
      foreach($data as $name => $value) {
        $this->$name = $value;
      }
    }
    $temp = $this->challengeFunct;
    $this->challenge = $temp[0];
    $this->answer = $temp[1];
    $_SESSION[$this->session] = $this->answer;
  }

  public static function verify($input) {
    if (isset($_SESSION[$this->session]) && $input == $_SESSION[$this->session]) {
      return true;
    }
    return false;
  }

  protected function mathChallenge($operators = array('+'), $min=0, $max=10) {
    $operator = array_rand($operators);
    $operands = array(rand($min,$max), rand($min,$max));
    if (($operands[0] < $operands[1]) && ('-' == $operator)){
      $operands = array($operands[1], $operands[0]);
    }
    $challenge = $operands[0] . $operators[$operator] . $operands[1]."=";
    switch ($operators[$operator]) {
      case '+':
        $anwser = $operands[0]+$operands[1];
        break;
      case '*':
        $anwser = $operands[0]*$operands[1];
        break;
      case '-':
        $anwser = $operands[0]-$operands[1];
        break;
      case '/':
        $anwser = $operands[0]/$operands[1];
        break;
      case '^':
        $anwser = pow($operands[0], $operands[1]);
        break;
    }
    return array($challenge, $anwser);
  }

  protected function randomCharChallenge(){
    $chars = 'abcdefghijklmnopqrstuvwxyz';
    $chars = str_split($chars);
    $index = array_rand($chars, $this->length);
    $challenge = array();
    for ($i=1; $i <= $this->length; $i++){
      $challenge[] = $chars[$index[$i-1]];
    }
    $challenge = implode($challenge);
    return array($challenge, $challenge);
  }



  public function create() {
    // get challenge text
    $challenge = $this->challenge;
    if ($this->width == null && $this->height == null){
      // get sizes
      $box = @imagettfbbox($this->fontSize, 0, $this->font, $challenge);
      $this->width = abs($box[4] - $box[0]) +15;
      $this->height = abs($box[5] - $box[1]) +10;
    }
    // create image
    $this->im = @imagecreate($this->width, $this->height)
      or die("Cannot Initialize new GD image stream");

    // back ground & foreground
    $background_color = imagecolorallocate($this->im, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
    $text_color = imagecolorallocate($this->im, $this->textColor[0],$this->textColor[1],$this->textColor[2]);
    $challenge = str_split($challenge);
    $i = 0;
    $temp = 0;
    foreach ($challenge as $char){
      $char = $this->createChar($char);
      $y=0;
      if (imagesy($char) <= 20){
        $y+=10;
      }
      imagecopymerge($this->im,
                $char,
                ($temp+5), $y, 0, 0, imagesx($char), imagesy($char), 100);
      $i++;
      $temp += imagesx($char);
    }
    //$this->im = $this->createChar("=");
    $this->noise();
    $this->filter();
  }

  protected function createChar($char){
    $box = @imagettfbbox($this->fontSize, 0, $this->font, $char);
    $width = abs($box[4] - $box[0])+5;
    $height = abs($box[5] - $box[1])+5;
    $im = @imagecreate($width, $height)
      or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
    $text_color = imagecolorallocate($im,     $this->textColor[0],$this->textColor[1],$this->textColor[2]);
    imagettftext($im, $this->fontSize, rand($this->letterRotation[0],$this->letterRotation[1]), 0, $height, $text_color, $this->font, $char);
    return $im;
  }

  protected function noise(){
    $line_color = imagecolorallocate($this->im, $this->lineColor[0], $this->lineColor[1], $this->lineColor[2]);
    for($i=0;$i<rand($this->lines[0], $this->lines[1]);$i++) {
        imageline($this->im,0,rand()%50,200,rand()%50,$line_color);
    }
    $pixel_color = imagecolorallocate($this->im, $this->foregroundColor[0], $this->foregroundColor[1], $this->foregroundColor[2]);
    for($i=0;$i<rand($this->dots[0], $this->dots[1]);$i++) {
      imagesetpixel($this->im,rand()%200,rand()%50,$pixel_color);
    }

  }

  protected function filter(){
    imagefilter($this->im, IMG_FILTER_SMOOTH, 25);
  }

  public function img() {
    ob_start();
    imagejpeg($this->im); //your code
    $img = ob_get_contents();
    ob_end_clean();
    imagedestroy($this->im);
    return "data:image/jpeg;base64,".base64_encode($img);
  }

  public function render (){
    if (isset($this->im)){
      // render image
      if (!isset($_GET['debug'])) {
        header("Content-Type: image/png");
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
      }
      imagepng($this->im);
      imagedestroy($this->im);
    } else {
      trigger_error("Must create image before rendering", E_USER_ERROR);
    }
  }
}
