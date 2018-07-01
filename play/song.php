<?php
class Song
{
    protected $name;
    protected $length;
    protected $img;
    protected $url;
    protected $id;
    public $start;

    public function __construct($name, $length, $img, $url, $id)
    {
      $this->name = $name;
      $this->length = $length/1000;
      $this->img = $img;
      $this->url = $url;
      $this->id = $id;
    }

    public function getName(){
      return $this->name;
    }
    public function getLength(){
      return $this->length;
    }
    public function getImg(){
      return $this->img;
    }
    public function getUrl(){
      return $this->url;
    }
    public function getId(){
      return $this->id;
    }
    public function getStart(){
      return $this->start;
    }

    public function setStart($time){
      $this->start = $time;
    }
}
?>
