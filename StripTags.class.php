class StripTags {
    private $tags;
    private $dom;
    function __construct($html, $tags = null) {
  // Setup tags
	$this->setTags($tags);
	
	//Intialise document using provided HTML
	$doc = new DOMDocument();
	@$doc->loadHTML($html);         //suppress invalid HTML warnings
	$doc_elem = $doc->documentElement;
    
	$this->traverseAndRemove($doc_elem);
    
	$this->dom = $doc; //->saveHTML();
    }
    public function __toString() {
	$str = $this->dom->saveHTML();
	$str = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', "", $str);
	$str = preg_replace("/^<html><body>/", "", trim($str));
	$str = preg_replace("/<\/body><\/html>$/", "", trim($str));
	return $str;
    }
    private function setTags($t) {
    	if (empty($t) or !is_array($t)) {
	    $this->tags = array("html", "body", "p", "span", "div", "img", "b", "i", "em", "strong", "h1", "h2", "h3", "h4", "a", "pre");
	} else {
	    $this->tags = $t;
	}
	if (!in_array("html",$this->tags)) {
	    $this->tags[] = "html";
	}
	if (!in_array("body",$this->tags)) {
	    $this->tags[] = "body";
	}
    }
    
    private function traverseAndRemove(&$elem) {
	if ($elem->nodeType === XML_ELEMENT_NODE) {
	    $tagName = $elem->tagName;
	    if (!in_array($tagName, $this->tags)) {
		$elem->parentNode->removeChild($elem);
		return;
	    }
	    
	    if ($elem->hasAttributes()) {
		$attrs = $elem->attributes;
		for ($i=0,$max=$attrs->length; $i<$max; $i++) {
		    $name = $attrs->item($i)->name;
		    if (in_array($name, array("onload", "onclick","onmouseover","onmousemove","onmousehover","onmousedown","onmouseup", "onunload"))) {
			$elem->removeAttribute($name);
			$max--;
			$i--;
		    }
		    if (in_array($name,array("src", "href")) and preg_match("/^javascript:/i", $attrs->item($i)->value)) {
			$elem->removeAttribute($name);
			$max--;
			$i--;
		    }
		}
	    }
	}
	
	if ($elem->hasChildNodes()) {
	    $children = $elem->childNodes;
	    for ($i=0, $max=$children->length; $i<$max; $i++) {
		
		$this->traverseAndRemove($children->item($i));
		
		if ($children->length < $max) {
		    $i -= ($max - $children->length);
		    $max = $children->length;
		}
	    }
	}
    }
}
