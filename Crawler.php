<?php
//class Crawler
class Crawler {
       
    protected $markup = "";
	/**
	 * Constructor
	 * @param string $url
	 */
    public function __construct($url) {
        $this->markup = $this->getMarkup($url);
    }
    
    /**
	 * Function getMarkup
	 * @param string $url
	 * @return string stream
	 */
    public function getMarkup($url) {
        return file_get_contents($url);
    }
    
    /**
	 * Function get
	 * @param string $type
	 * @return results of executed user function
	 */
    public function get($type) {
        $method = "_get_{$type}";
        if (method_exists($this, $method)) {
            return call_user_func(array($this, $method));
        }
    }
    
    /**
	 * Function getCurlResponse
	 * @param string $url
	 * @return array
	 */
    public function getCurlResponse($url)
    {
		$response = array();
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);                
        if(!curl_errno($ch))
		{
		    $info = curl_getinfo($ch);
		    $response['time'] = $info['total_time'];
		}		
		curl_close($ch);
		$response['html'] = $html;
        return $response;
	}    

    /**
	 * Function _get_links - finds all the hyperlinks
	 * @return string output
	 */
    protected function _get_links() {
        if (!empty($this->markup)) {
            preg_match_all('/<a [^>]*href="?([^ ">]+)"?/i', $this->markup, $links);
            return !empty($links[1]) ? $links[1] : FALSE;
        }
    }
 
}
