<?php
namespace core\system;
use core\system\router;
class Application
{
    public static function initialize()
    {

        $GLOBALS['CONFIG'] =  include dirname( __FILE__ ).'/config.php';
        
        self::SecurityHeader();
        self::CheckReporting($GLOBALS['CONFIG']['DebugMode']);    
        $DynamicRoot = self::GenerateSitePath();
        define( '__Dynamic_PATH__',$DynamicRoot );
        define( '__LIB_PATH__', __SITE_PATH__.'/lib' );
        define( '__Controller_PATH__', __LIB_PATH__ . '/controller/' );
        define( '__Model_PATH__', __LIB_PATH__ . '/model/' );
        define( '__View_PATH__', __LIB_PATH__ . '/view/' );
        define( '__Packages_PATH__', __LIB_PATH__ . '/packages/' );
        
        router::DoRoute();
       
    }

    public static function SecurityHeader()
    {
        header( 'X-Frame-Options: SameOrigin' );
        header( 'X-XSS-Protection: 1' );
        header( 'X-Powered-By: Dynamic' );
        header( "Content_security_policy : default-src 'self' style-src 'self' 'unsafe-inline';" );
    }

    public static function CheckReporting($status)
    {
        if ($status === TRUE ) {
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'On' );
        } else {
            error_reporting( 0 );
            ini_set( 'display_errors', 'Off' );
        }
    }
    
	private static function GenerateSitePath(){
    	$DOCUMENT_ROOT_fix = str_replace('/','\\',$_SERVER['DOCUMENT_ROOT']);
    	$site_path_fix = str_replace('/','\\',__SITE_PATH__);
    	$last = str_replace($DOCUMENT_ROOT_fix,'',$site_path_fix);
    	$last = str_replace('\\', '/', ltrim($last,'\\')); 	
   	
    	if(trim($last) != '' && substr($last, 0 , -1) != '/')
    		$last .= '/';
    	return $last;
    }

}