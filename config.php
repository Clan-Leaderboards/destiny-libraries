<?php

	#
	# config.php
	# Configuration & Global variables for CL-Destiny libraries
	#

# Check for debugging flags
if ($argc>0 && $argv[1]=="--debug") {
		define(DEBUG_FLAG, TRUE);
	} else {
		define(DEBUG_FLAG, FALSE);
}





# define whether the application configuration is encoded
define("CFG_ENCODED", TRUE);
# define location of file containing application identifiers
define("APP_DATA_FILE", "./appdata.cfg");
# define base protocol used to query API
define("PROTOCOL", "https://");
# define server and FQDN to access API from
define("API_SERVER", "www.bugnie.net");
# define origin header
define("ORIGIN_HEADER", "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
# define application version
define("VERSION_NUMBER", "0.1𝛼");
# define release number
define("RELEASE_NUMBER", date("YmdHis", filemtime(".")));
# define application name
define("APPLICATION_NAME", "CL Destiny Libraries");
# define complete User Agent
define("USER_AGENT", APPLICATION_NAME." ".VERSION_NUMBER."(".RELEASE_NUMBER.")");


# check to ensure application identifier file exists, and read it in if it does
if (is_readable(APP_DATA_FILE)) {

	$cfgfp = @fopen(APP_DATA_FILE, "r");

	# Import, decode, and define CLIENT_ID
	if ($pCLIENT_ID=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CLIENT_ID", trim(base64_decode($pCLIENT_ID)));
		} else {
			define("CLIENT_ID", trim($pCLIENT_ID));
		}
		unset($pCLIENT_ID);
	} else {
		error_exit("CLIENT_ID not found");
	}

	# Import, decode, and define API_KEY
	if ($pAPI_KEY=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("API_KEY", trim(base64_decode($pAPI_KEY)));
		} else {
			define("API_KEY", trim($pAPI_KEY));
		}
		unset($pAPI_KEY);
	} else {
		error_exit("API_KEY not found");
	}

	# Import, decode, and define SECRET
	if ($pSECRET=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("SECRET", trim(base64_decode($pSECRET)));
		} else {
			define("SECRET", trim($pSECRET));
		}
		unset($pSECRET);
	} else {
		error_exit("SECRET not found");
	}

	fclose($cfgfp);
	unset($cfgfp);
} else {
	error_exit("Application data file not found");
}

function error_exit( $error_code = "undefined" ) {
	print_r(error_get_last().":".$error_code."\n");
	exit(1);

}


#
# String functions
#

    function after ($pthis, $inthat)
    {
        if (!is_bool(strpos($inthat, $pthis)))
        return substr($inthat, strpos($inthat,$pthis)+strlen($pthis));
    };

    function after_last ($pthis, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $pthis)))
        return substr($inthat, strrevpos($inthat, $pthis)+strlen($pthis));
    };

    function before ($pthis, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $pthis));
    };

    function before_last ($pthis, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $pthis));
    };

    function between ($pthis, $that, $inthat)
    {
        return before ($that, after($pthis, $inthat));
    };

    function between_last ($pthis, $that, $inthat)
    {
     return after_last($pthis, before_last($that, $inthat));
    };


function pDEBUG( $pDebugVar="D_ALL" ) {

	function pDisplayDebug($pDisplayDebugVar) {
		$pBackTrace=debug_backtrace()[0];
		$pSource=file($pBackTrace["file"]);
		$pVarName=between ("(", ")", $pSource[$pBackTrace["line"]-1]);
		print_r($pVarName.": ".$pDisplayDebugVar."\n");
	}

	if ($pDebugVar=="D_ALL") {
		pDisplayDebug(CLIENT_ID);
		pDisplayDebug(API_KEY);
		pDisplayDebug(SECRET);
		pDisplayDebug(APP_DATA_FILE);
		pDisplayDebug(CFG_ENCODED);
		pDisplayDebug(PROTOCOL);
		pDisplayDebug(API_SERVER);
		pDisplayDebug(ORIGIN_HEADER);
		pDisplayDebug(RELEASE_NUMBER);
		pDisplayDebug(VERSION_NUMBER);
		pDisplayDebug(APPLICATION_NAME);
		pDisplayDebug(USER_AGENT);

	} else {
		pDisplayDebug($pDebugVar);
	}
}

if (DEBUG_FLAG) {
	pDEBUG( "D_ALL" );
}

?>