<?php
if (!ini_get('safe_mode')) {
set_time_limit(240);
}

function __autoload($class_name) {
    if(file_exists($class_name . '.php')) {
        require_once($class_name . '.php');    
    } else {
        throw new Exception("Unable to load $class_name.");
    }
} 

if (!empty($_POST['url']) && $_POST['url'] != '') {
    $url = $_POST['url'];

    if (substr($url, 0, 4) != 'http')
    $url = 'http://' . $url;

    try {
        $crawl = new Crawler($url);    
    } catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }
    $links = $crawl->get('links');
    $i = 1;
    $j = 0;
    
    if (!empty($links)) {
		echo "<table cellpadding='5'>";
		echo "<tr><td id='title'>URL</td><td id='title'>Count - IMG tags</td><td id='title'>Total Time</td></tr>";
        foreach ($links as $link) {
            if ($j % 2 == 0)
                $style = "class='evenrow'";
            else
                $style = "class='oddrow'";
            if ($link[0] == "'")
                $link = substr($link, 1, -1);
            if ($link[0] == "/")
                $link = $_POST['url'] . $link;	                        
            
            $response = $crawl->getCurlResponse($link);
            $html = $response['html'];            
            $dom = new DOMDocument();                    
            @$dom->loadHTML($html);

            $k=0;
            foreach($dom->getElementsByTagName('img') as $lnk) {                
                if($lnk->getAttribute('src')!='');
                    $k++;        
            }
            echo "<tr><td " . $style . ">" . $link . "</td><td " . $style . ">" . $k . "</td><td " . $style . ">" . $response['time'] . "</td></tr>";
            $j++;
        }
        echo "</table>";
    }
    else 
       echo "<table cellpadding='5'><tr><td colspan='3'>No results! try with a valid URL</td></tr></table>";
    
}

