<?php

/* Classes */
include_once('Singleton.php');

class ParserUrl extends Singleton {

	protected $url;
	protected $content;

	public function setUrl($url){
		
		if ($this->url != $url){

			$this->url = $url;
			$this->updateContent();
		}
	}

  	public function getUrl(){
	    return $this->url;
	 }

  	public function updateContent(){
		$this->content = file_get_contents($this->url);
	}

	public function getFrag($start, $end){
		$content = $this->content;
		$first_step = explode( $start , $content );
		if (count($first_step) >= 2){
			$second_step = explode($end , $first_step[1] );
			if ($second_step){
				return trim($second_step[0]);
			}
		}
		
		return false;
	}

	public function getImgiInFrag($frag){
		$doc = new DOMDocument();
	    @$doc->loadHTML($frag);

	    $tags = $doc->getElementsByTagName('img');

	    $rtn = array();

	    foreach ($tags as $tag) {
	            array_push($rtn, $tag->getAttribute('src'));
	    }

	    return $rtn;
	}

	public function getName($url){
		$this->setUrl($url);
  		return $this->getFrag('<h1 class="underlined-h1">', '</h1>');
  	}

  	public function getDesc($url){
		$this->setUrl($url);
  		return $this->getFrag('<p class="bodytext">', '</p>');
  	}

  	public function getImg($url){
		$this->setUrl($url);
  		$imgFrag = $this->getFrag('<div class="image-slider-wrapper">', '</div>');
  		$img = $this->getImgiInFrag($imgFrag);
  		if (!empty($img)){
  			return implode(",", $img);
  		}else{
  			return "";
  		}
  	}


}

function name($name = null, $url = null){
	$ParserUrl = ParserUrl::instance();

	if ($name !== ""){
		return $name;
	}

	return $ParserUrl->getName($url);
}

function desc($desc = null, $url = null){
	$ParserUrl = ParserUrl::instance();

	if ($desc !== ""){
		return $desc;
	}

	return $ParserUrl->getDesc($url);
}

function short_desc($short_desc = null, $url = null){
	$ParserUrl = ParserUrl::instance();

	if ($short_desc !== ""){
		return $short_desc;
	}

	return $ParserUrl->getDesc($url);
}

function image($image = null, $url = null){
	$ParserUrl = ParserUrl::instance();

	if ($image !== ""){
		return $image;
	}

	return $ParserUrl->getImg($url);
}


?>
