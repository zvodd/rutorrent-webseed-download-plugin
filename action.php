<?php
require_once( '../../php/rtorrent.php' );
require_once( '../../php/Torrent.php' );

// Add to "conf.php"
// $webseedurl = "http://example.com/downloads";


eval( getPluginConf( 'webseedsource' ) );

class WeebSeedTorrent extends Torrent {
		protected $pointer = 0;
	
		public function __construct($torrent, $webseedurl) 
		{
			$torrentdata = get_object_vars($torrent);
			foreach ($torrentdata as $key => $value){
				$this->{$key} = $value;
			}
			$this->{"url-list"} = [$webseedurl];
			$this->announce = null;
			$this->private = 1;
			return;
		}
	
	public function getName($default) {
		if(is_null($this->name)){
			return $default;
		}
		else{
			return $this->name;	
		}
	}
}


function serve_file($webseedurl){
	if(isset($_REQUEST['result']))
		cachedEcho('noty(theUILang.cantFindTorrent,"error");',"text/html");
	if(isset($_REQUEST['hash']))
	{
		$torrent = rTorrent::getSource($_REQUEST['hash']);
		if($torrent){
			$newtorrent = new WeebSeedTorrent($torrent, $webseedurl);
			
			if (is_null($filename)){
				$filename = 'webseed_'.$newtorrent->getName($_REQUEST['hash']).'.torrent';
			}
			if(isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'],'MSIE')){
				$filename = rawurlencode($filename);
			}
				
			header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
			cachedEcho( $newtorrent->__toString(), 'application/x-bittorrent', true );
		}
	}
}

serve_file($webseedurl);
header("HTTP/1.0 302 Moved Temporarily");
header("Location: ".$_SERVER['PHP_SELF'].'?result=0');
