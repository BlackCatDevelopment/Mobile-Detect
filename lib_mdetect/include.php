<?php

/**
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author          Black Cat Development
 *   @copyright       2013, Black Cat Development
 *   @link            http://blackcat-cms.org
 *   @license         http://www.gnu.org/licenses/gpl.html
 *   @category        CAT_Modules
 *   @package         lib_mdetect
 *
 */

if (defined('CAT_PATH')) {
    if (defined('CAT_VERSION')) include(CAT_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php');
} else {
    $subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));    $dir = $_SERVER['DOCUMENT_ROOT'];
    $inc = false;
    foreach ($subs as $sub) {
        if (empty($sub)) continue; $dir .= '/'.$sub;
        if (file_exists($dir.'/framework/class.secure.php')) {
            include($dir.'/framework/class.secure.php'); $inc = true;    break;
        }
    }
    if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}

global $mobile, $md_map_devices;

// mdetect function name => text
$md_map_devices = array(
    'DetectIphone' => 'iPhone',
    'DetectIpod' => 'iPod',
    'DetectIpad' => 'iPad',
    'DetectAndroidTablet' => 'Android Tablet',
    'DetectAndroidWebKit' => 'Android Webkit',
    'DetectAndroid' => 'Android Device',
    'DetectS60OssBrowser' => 'Nokia S60',
    'DetectSymbianOS' => 'Symbian OS (Nokia)',
    'DetectWindowsPhone7' => 'Windows Phone 7',
    'DetectWindowsMobile' => 'Windows Mobile',
    'DetectBlackBerryTablet' => 'BlackBerry Tablet',
    'DetectBlackBerry' => 'BlackBerry',
    'DetectPalmOS' => 'PalmOS',
    'DetectPalmWebOS' => 'PalmOS Webkit',
    'DetectGarminNuvifone' => 'Garmin',
    'DetectBrewDevice' => 'Brew device',
    'DetectDangerHiptop' => 'DangerHiptop',
    'DetectWapWml' => 'Wap device',
    'DetectKindle' => 'Kindle',
    'DetectSonyPlaystation' => 'Sony Playstation',
    'DetectNintendo' => 'Nintendo',
    'DetectXbox' => 'Xbox',
    'DetectOperaMobile' => 'Opera Mobile',
    'DetectWebkit' => 'Webkit',
);
// local function name => mdetect function (other func names)
$md_map_functions = array(
    'isJavascript' => 'DetectTierIphone',
    'isMobile' => 'DetectMobileLong',
    'isSmartPhone' => 'DetectSmartphone',
    'isTablet' => 'DetectTierTablet',
    'isWap' => 'DetectWapWml'
);
// local function name => mdetect function (will replace 'Detect' with 'is')
$md_map_original_functions = array(
    'DetectIphone',
    'DetectIpod',
    'DetectIpad',
    'DetectIphoneOrIpod',
    'DetectIos',
    'DetectAndroid',
    'DetectAndroidPhone',
    'DetectAndroidTablet',
    'DetectAndroidWebKit',
    'DetectGoogleTV',
    'DetectWebkit',
    'DetectWindowsPhone',
    'DetectWindowsPhone7',
    'DetectWindowsPhone8',
    'DetectWindowsMobile',
    'DetectBlackBerry',
    'DetectBlackBerry10Phone',
    'DetectBlackBerryTablet',
    'DetectBlackBerryWebKit',
    'DetectBlackBerryTouch',
    'DetectBlackBerryHigh',
    'DetectBlackBerryLow',
    'DetectS60OssBrowser',
    'DetectSymbianOS',
    'DetectPalmOS',
    'DetectPalmWebOS',
    'DetectWebOSTablet',
    'DetectOperaMobile',
    'DetectOperaAndroidPhone',
    'DetectOperaAndroidTablet',
    'DetectKindle',
    'DetectAmazonSilk',
    'DetectGarminNuvifone',
    'DetectBada',
    'DetectTizen',
    'DetectMeego',
    'DetectDangerHiptop',
    'DetectSonyMylo',
    'DetectMaemoTablet',
    'DetectArchos',
    'DetectGameConsole',
    'DetectSonyPlaystation',
    'DetectGamingHandheld',
    'DetectNintendo',
    'DetectXbox',
    'DetectBrewDevice',
    'DetectWapWml',
    'DetectMidpCapable',
    'DetectSmartphone',
    'DetectMobileQuick',
    'DetectMobileLong',
    'DetectTierTablet',
    'DetectTierIphone',
    'DetectTierRichCss',
    'DetectTierOtherPhones',
);

require_once dirname(__FILE__).'/mdetect/mdetect.php';
$mobile = new uagent_info();

// create the isXXX functions
foreach( $md_map_functions as $f => $o )
    eval( "function $f() { global \$mobile, \$o; return \$mobile->$o(); }" );

foreach( $md_map_original_functions as $o )
{
    $f = str_replace('Detect','is',$o);
    if(!function_exists($f))
        eval( "function $f() { global \$mobile, \$o; return \$mobile->$o(); }" );
}

function whatMobile() {
    global $mobile, $md_map_devices;
    if ($mobile->DetectMobileQuick())
    {
        foreach( $md_map_devices as $f => $t )
            if ( $mobile->$f() ) { return $t; break; }
        return 'unkown mobile device';
    }
    else
    {
        return 'no mobile device';
    }
}