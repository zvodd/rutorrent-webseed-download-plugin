<?php
require_once( '../../php/rtorrent.php' );
require_once( '../../php/Torrent.php' );
require_once( "../../php/xmlrpc.php" );

// Update conf.php with your parameters before running !

eval( getPluginConf( 'webseedsource' ) );

class WeebSeedTorrent extends Torrent {
		protected $pointer = 0;
	
		public function __construct($torrent, $dlpath, $webseedurl) 
		{
			$torrentdata = get_object_vars($torrent);
			foreach ($torrentdata as $key => $value){
				$this->{$key} = $value;
			}
			$url = $webseedurl;
			if ($dlpath != "") {
				$url = $url . "/" . $dlpath . "/";
			}
			$this->{"url-list"} = [$url];
			$this->{"announce-list"} = [];
			$this->{"announce"} = null;
			$this->{"private"} = 1;
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


function serve_file($webseedurl, $webseedbase){
	if(isset($_REQUEST['result']))
		cachedEcho('noty(theUILang.cantFindTorrent,"error");',"text/html");
	if(isset($_REQUEST['hash']))
	{
		$torrent = rTorrent::getSource($_REQUEST['hash']);
		if($torrent){
			$req = new rXMLRPCRequest();
			$req->addCommand(new rXMLRPCCommand( "d.get_directory", $_REQUEST['hash']));
			$req->run();
			$basepath = $req->val[0];
			$req = new rXMLRPCRequest();
			$req->addCommand(new rXMLRPCCommand( "d.get_base_filename", $_REQUEST['hash']));
			$req->run();
			$filename = $req->val[0];
			$basepath = str_replace($webseedbase, "", $basepath);
			$basepath = trim(str_replace($filename, "", $basepath), "/");
			$newtorrent = new WeebSeedTorrent($torrent, $basepath, $webseedurl);
			toLog("Generating torrent with url_list:" . implode(",", $newtorrent->{"url-list"}));
			
			if (is_null($filename)){
				$filename = 'webseed_'.$newtorrent->getName($_REQUEST['hash']).'.torrent';
			}
			if(isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'],'MSIE')){
				$filename = rawurlencode($filename);
			}
			if ($newtorrent)
				$newtorrent->send();
		}
	}
}

serve_file($webseedurl, $webseedbase);
header("HTTP/1.0 302 Moved Temporarily");
header("Location: ".$_SERVER['PHP_SELF'].'?result=0');
