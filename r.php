<?php
//$referersource = $_SERVER['HTTP_REFERER'];
//echo "From $referersource";
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
require 'vendor/autoload.php';
use donatj\phpuseragentparser;
use Aws\DynamoDb\DynamoDbClient;
$client = DynamoDbClient::factory(array(
    'profile' => 'default',
    'region' => 'us-east-1'
));
$link   = mysql_connect('tamemcluster.c2brsqgmxqob.us-east-1.rds.amazonaws.com', 'trafficavenueweb', 'Miami;06082011');
if (!$link) {
    die('Connexion impossible : ' . mysql_error());
}
mysql_select_db(trafficavenue) or die("connection error");
//
date_default_timezone_set('America/New_York');
//
$time  = time();
$agent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
    $testip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $testip = $_SERVER['REMOTE_ADDR'];
}
$realip      = explode(',', $testip);
$ip          = $realip[0];
$agent       = $_SERVER['HTTP_USER_AGENT'];
$agentv      = urlencode($agent);
$currenthour = date('H');
$campaign    = mysql_real_escape_string($_GET["campaign"]);
$clicid      = mysql_real_escape_string($_GET["clicid"]);
$r           = mysql_real_escape_string($_GET["r"]);
$country     = mysql_real_escape_string($_GET["country"]);
//Redirect sur compte Alex si as de campagnes
if ($campaign == "") {
    $campaign = "540939";
}
$zone = "40$campaign";
//
if ($agent == "") {
    mysql_close($link);
    exit;
}
$ua_info  = parse_user_agent($agent);
$browser  = $ua_info['browser'];
$platform = $ua_info['platform'];
//
if ($platform == "") {
    $platform = "Undefined";
}
if ($browser == "") {
    $browser = "Undefined";
}
$miniclicid = hash('crc32', mt_rand());
//
if ($platform == "Undefined") {
    $r = "ua";
} //
if ($platform == "Windows") {
    $ciblecomputer = "windows";
} //
if ($platform == "Linux") {
    $ciblecomputer = "linux";
    $mobile        = "1";
} //
if ($platform == "Macintosh") {
    $ciblecomputer = "macos";
} //
if ($platform == "Chrome OS") {
    $ciblecomputer = "other_desktopos";
}
if ($platform == "Android") {
    $ciblecomputer = "android";
    $mobile        = "1";
} //
if ($platform == "iPhone") {
    $ciblecomputer = "iphone";
    $mobile        = "1";
} //
if ($platform == "iPad") {
    $ciblecomputer = "ipad";
    $mobile        = "1";
} //
if ($platform == "Windows Phone OS") {
    $ciblecomputer = "windowsphone";
    $mobile        = "1";
} //
if ($platform == "Windows Phone") {
    $ciblecomputer = "windowsphone";
    $mobile        = "1";
} //
if ($platform == "Kindle") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Kindle Fire") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Blackberry") {
    $ciblecomputer = "blackberry";
    $mobile        = "1";
} //
if ($platform == "Playbook") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Nintendo 3DS") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "New Nintendo 3DS") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Nintendo Wii") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Nintendo WiiU") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "PlayStation 3") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "PlayStation 4") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "PlayStation Vita") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Xbox 360") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($platform == "Xbox One") {
    $ciblecomputer = "other_mobile";
    $mobile        = "1";
}
if ($ciblecomputer == "") {
    $ciblecomputer = "other_desktopos";
}
//
if ($browser == "Firefox / Iceweasel") {
    $ciblebrowser = "firefox";
}
if ($browser == "Firefox") {
    $ciblebrowser = "firefox";
}
if ($browser == "Safari") {
    $ciblebrowser = "safari";
}
if ($browser == "AppleWebKit") {
    $ciblebrowser = "safari";
}
if ($browser == "Internet Explorer") {
    $ciblebrowser = "internetexplorer";
}
if ($browser == "Edge") {
    $ciblebrowser = "internetexplorer";
}
if ($browser == "MSIE") {
    $ciblebrowser = "internetexplorer";
}
if ($browser == "Chrome") {
    $ciblebrowser = "chrome";
}
if ($browser == "Opera") {
    $ciblebrowser = "other_desktop";
}
if ($browser == "Opera Next") {
    $ciblebrowser = "other_desktop";
}
if ($browser == "" and $mobile != "1") {
    $ciblebrowser = "other_desktop";
}
if ($mobile == "1") {
    $ciblebrowser = "";
}
if ($r == '') {
    mysql_close($link);
    exit;
}
$rechcomptew2 = "SELECT account,min_cpm,return_url,target,status from pub_campaigns where id = '$campaign' limit 1";
$reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
$data          = mysql_fetch_array($reqcomptew2);
$account       = $data['account'];
$accounttestzp = $data['account'];
$min_cpm       = $data['min_cpm'];
$return_url    = $data['return_url'];
$targetpub     = $data['target'];
$target        = $data['target'];
$status        = $data['status'];
if ($return_url == "") {
    $return_url = "http://qjs39.totalspeedbrand.com/?noaudio=1&noexit=1&nodl=nodl";
}
if ($return_url == "http://") {
    $return_url = "http://qjs39.totalspeedbrand.com/?noaudio=1&noexit=1&nodl=nodl";
}
//
if ($account == "") {
    mysql_close($link);
    //header("Referer:  http://www.trfkav.com/index.php \r\n");
    //header("Location: http://qjs39.totalspeedbrand.com/?s1=noaccount&noaudio=1&noalert=1&noexit=1&nodl=nodl");
    echo "<meta http-equiv=\"refresh\" content=\"0;url=http://qjs39.totalspeedbrand.com/?s1=noaccount&noaudio=1&noalert=1&noexit=1&nodl=nodl\"/>";
    exit;
}
if ($r == "ip") {
    $rpm    = 0;
    $soldto = "Returned IP";
    $result = $client->putItem(array(
        'TableName' => 'incming',
        'Item' => array(
            'clicid' => array(
                'S' => $clicid
            ),
            'ip' => array(
                'S' => $ip
            ),
            'country' => array(
                'S' => $country
            ),
            'agent' => array(
                'S' => $agent
            ),
            'time' => array(
                'N' => $time
            ),
            'rpm' => array(
                'N' => $rpm
            ),
            'soldto' => array(
                'S' => $soldto
            ),
            'campaign' => array(
                'S' => $campaign
            ),
            'urlsent' => array(
                'S' => $return_url
            ),
            'platform' => array(
                'S' => $platform
            ),
            'browser' => array(
                'S' => $browser
            )
        )
    ));
    echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
    exit;
}
//
if ($r == "ua") {
    $rpm    = 0;
    $soldto = "Returned UA";
    $result = $client->putItem(array(
        'TableName' => 'incming',
        'Item' => array(
            'clicid' => array(
                'S' => $clicid
            ),
            'ip' => array(
                'S' => $ip
            ),
            'country' => array(
                'S' => $country
            ),
            'agent' => array(
                'S' => $agent
            ),
            'time' => array(
                'N' => $time
            ),
            'rpm' => array(
                'N' => $rpm
            ),
            'soldto' => array(
                'S' => $soldto
            ),
            'campaign' => array(
                'S' => $campaign
            ),
            'urlsent' => array(
                'S' => $return_url
            ),
            'platform' => array(
                'S' => $platform
            ),
            'browser' => array(
                'S' => $browser
            )
        )
    ));
    echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
    exit;
}
//
$countrysearch = $country;
if ($targetpub == "Adult") {
    $adu           = "-Adult";
    $countrysearch = "$country$adu";
}
if ($targetpub == "Beauty") {
    $adu           = "-Beauty";
    $countrysearch = "$country$adu";
}
if ($targetpub == "TechSupport") {
    $adu           = "-TechSupport";
    $countrysearch = "$country$adu";
}
// Zeropark main query
//$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
//$url = "http://feed.zeropark.com/zeroclick?domain=$campaign.mng-traff.com&ip=$ip&useragent=$agentv&keywords=&feedid=99485ff0-52df-11e5-a406-0afe289da1cd";
//$xml = file_get_contents($url, false, $context);
//$xml = simplexml_load_string($xml);
//$bidzp = $xml->bid;
//$redirecturlzp = $xml->redirecturl;
//$bidzp = floatval($bidzp);
//
if ($r == "c") {
    if ($mobile == "1") {
        $rechcomptew2 = "SELECT total AS totalcamp from adv_campaigns_total where country = '$countrysearch' and system  = '$ciblecomputer' limit 1";
        $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
        $data       = mysql_fetch_array($reqcomptew2);
        $totalcamps = $data['totalcamp'];
    } else {
        $rechcomptew2 = "SELECT total AS totalcamp from adv_campaigns_total where country = '$countrysearch' and system  = '$ciblecomputer$ciblebrowser' limit 1";
        $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
        $data       = mysql_fetch_array($reqcomptew2);
        $totalcamps = $data['totalcamp'];
    }
    //
    if ($totalcamps < 1 and $targetpub != "RON" and $targetpub != "Adult") //Remettre en RON si Defaut d'autre chose mais pas Adult
        {
        $countrysearch = $country;
        if ($mobile == "1") {
            $rechcomptew2 = "SELECT total AS totalcamp from adv_campaigns_total where country = '$country' and system    = '$ciblecomputer' limit 1";
            $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
            $data       = mysql_fetch_array($reqcomptew2);
            $totalcamps = $data['totalcamp'];
        } else {
            $rechcomptew2 = "SELECT total AS totalcamp from adv_campaigns_total where country = '$country' and system    = '$ciblecomputer$ciblebrowser' limit 1";
            $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
            $data       = mysql_fetch_array($reqcomptew2);
            $totalcamps = $data['totalcamp'];
        }
    }
    if ($totalcamps > 5) {
        $totalcamps = 5;
    }
    //
    if ($totalcamps < 1) {
        //Zeropark
        if ($return_url == "") {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "noaccc";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=http://qjs39.totalspeedbrand.com/?s1=nb1&noaudio=1&noalert=1&noexit=1&nodl=nodl\"/>";
            exit;
        } else {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "noaccc";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
            exit;
        }
    }
    $rand = rand(1, 20);
    if ($totalcamps == 1) {
        $selectcampaign = 1;
    }
    if ($totalcamps == 2) {
        if ($rand > 0 and $rand < 16) {
            $selectcampaign = 1;
        } else {
            $selectcampaign = 2;
        }
    }
    if ($totalcamps == 3) {
        if ($rand > 0 and $rand < 15) {
            $selectcampaign = 1;
        }
        if ($rand > 14 and $rand < 17) {
            $selectcampaign = 2;
        }
        if ($rand > 16) {
            $selectcampaign = 3;
        }
    }
    if ($totalcamps == 4) {
        if ($rand > 0 and $rand < 13) {
            $selectcampaign = 1;
        }
        if ($rand > 12 and $rand < 16) {
            $selectcampaign = 2;
        }
        if ($rand > 15 and $rand < 18) {
            $selectcampaign = 3;
        }
        if ($rand > 17) {
            $selectcampaign = 4;
        }
    }
    if ($totalcamps == 5) {
        if ($rand > 0 and $rand < 12) {
            $selectcampaign = 1;
        }
        if ($rand > 11 and $rand < 15) {
            $selectcampaign = 2;
        }
        if ($rand > 14 and $rand < 17) {
            $selectcampaign = 3;
        }
        if ($rand > 16 and $rand < 19) {
            $selectcampaign = 4;
        }
        if ($rand > 18) {
            $selectcampaign = 5;
        }
    }
    //if ($selectcampaign == 5) { $randcamp = rand(5,$totalcamps); $selectcampaign = $randcamp;}
    //
    $rechcomptew2 = "SELECT campaign AS selectedcampaignid from adv_campaigns_positions where country = '$countrysearch' and system='$ciblecomputer$ciblebrowser' and position = '$selectcampaign' limit 1";
    $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
    $data               = mysql_fetch_array($reqcomptew2);
    $idselectedcampaign = $data['selectedcampaignid'];
    //
    $rechcomptew2       = "SELECT presence from adv_blacklist where zone='$zone' and campaign='$idselectedcampaign' limit 1";
    $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
    $data       = mysql_fetch_array($reqcomptew2);
    $presencebl = $data['presence'];
    //BL avec 1 campaign
    if ($presencebl == "1" and $totalcamps == 1) {
        //Campaign 1 blacklist, no more bidders
        if ($return_url == "") {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "1blnmb";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=http://qjs39.totalspeedbrand.com/?noaudio=1&noalert=1&noexit=1&nodl=nodl\"/>";
            exit;
        } else {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "1blnmb";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
            exit;
        }
    } // Fin BL avec une camp
    // Hack pour renvoyer sur une autre campagne si la 1ere est BL
    if ($presencebl == "1" and $totalcamps > 1) {
        $selectcampaign = rand($totalcamps, 5);
        $rechcomptew2   = "SELECT campaign AS selectedcampaignid from adv_campaigns_positions where country = '$countrysearch' and system='$ciblecomputer$ciblebrowser' and position = '$selectcampaign' limit 1";
        $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
        $data               = mysql_fetch_array($reqcomptew2);
        $idselectedcampaign = $data['selectedcampaignid'];
        //
        $rechcomptew2       = "SELECT presence from adv_blacklist where zone='$zone' and campaign='$idselectedcampaign' limit 1";
        $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
        $data        = mysql_fetch_array($reqcomptew2);
        $presencebld = $data['presence'];
        //
        if ($presencebld == "1") {
            //la seconde est aussi BL, no more luck, redirect or nothing
            if ($return_url == "") {
                $rpm    = 0;
                $soldto = "Returned NB";
                $reason = "2blnm";
                $result = $client->putItem(array(
                    'TableName' => 'incming',
                    'Item' => array(
                        'clicid' => array(
                            'S' => $clicid
                        ),
                        'ip' => array(
                            'S' => $ip
                        ),
                        'country' => array(
                            'S' => $country
                        ),
                        'agent' => array(
                            'S' => $agent
                        ),
                        'time' => array(
                            'N' => $time
                        ),
                        'rpm' => array(
                            'N' => $rpm
                        ),
                        'soldto' => array(
                            'S' => $soldto
                        ),
                        'reason' => array(
                            'S' => $reason
                        ),
                        'campaign' => array(
                            'S' => $campaign
                        ),
                        'urlsent' => array(
                            'S' => $return_url
                        ),
                        'platform' => array(
                            'S' => $platform
                        ),
                        'browser' => array(
                            'S' => $browser
                        )
                    )
                ));
                echo "<meta http-equiv=\"refresh\" content=\"0;url=http://qjs39.totalspeedbrand.com/?noaudio=1&noalert=1&noexit=1&nodl=nodl\"/>";
                exit;
            } else {
                $rpm    = 0;
                $soldto = "Returned NB";
                $reason = "2blnm";
                $result = $client->putItem(array(
                    'TableName' => 'incming',
                    'Item' => array(
                        'clicid' => array(
                            'S' => $clicid
                        ),
                        'ip' => array(
                            'S' => $ip
                        ),
                        'country' => array(
                            'S' => $country
                        ),
                        'agent' => array(
                            'S' => $agent
                        ),
                        'time' => array(
                            'N' => $time
                        ),
                        'rpm' => array(
                            'N' => $rpm
                        ),
                        'soldto' => array(
                            'S' => $soldto
                        ),
                        'reason' => array(
                            'S' => $reason
                        ),
                        'campaign' => array(
                            'S' => $campaign
                        ),
                        'urlsent' => array(
                            'S' => $return_url
                        ),
                        'platform' => array(
                            'S' => $platform
                        ),
                        'browser' => array(
                            'S' => $browser
                        )
                    )
                ));
                echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
                exit;
            }
        }
    }
    $rechcomptew2 = "SELECT url,capping,cpm from adv_campaigns_details where id='$idselectedcampaign' limit 1";
    $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
    $data            = mysql_fetch_array($reqcomptew2);
    $urlselected     = $data['url'];
    $cappingselected = $data['capping'];
    $cpmselected     = $data['cpm'];
    $rpmselected     = $cpmselected / 1000;
    //
    $urlselected     = str_replace("{zone}", "$zone", $urlselected);
    $urlselected     = str_replace("{clicid}", "$clicid", $urlselected);
    $urlselected     = str_replace("{miniclicid}", "$miniclicid", $urlselected);
    $urlselected     = str_replace("{time}", "$time", $urlselected);
    $urlselected     = str_replace("{country}", "$country", $urlselected);
    $urlselected     = str_replace("{cost}", "$rpmselected", $urlselected);
    //
    //$rechcomptew2 = "SELECT views from capping where ip='$ip' and campaign='$idselectedcampaign' limit 1";
    //      $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>'.$rechcomptew2.'<br>'.mysql_error());
    //      $data = mysql_fetch_array($reqcomptew2);
    //      $currentcapping = $data['views'];
    //if ($cappingselected == 0) {$currentcapping = -1;}
    $currentcapping  = -1;
    if ($currentcapping < $cappingselected) {
        $nvviews = $currentcapping + 1;
        //capping temporairement retiré
        //$sql = "DELETE FROM capping where ip = '$ip' AND campaign = '$idselectedcampaign'";
        //$sql2 = "INSERT INTO capping (ip,campaign,views,updated) VALUES('$ip','$idselectedcampaign', '$nvviews','$time')";
        // use exec() because no results are returned
        //$dbcloud->exec($sql);
        //$dbcloud->exec($sql2);
        $rpm     = 0;
        $percent = (($rpmselected / 100) * 40);
        $revenu  = $rpmselected - $percent;
        //
        $result  = $client->putItem(array(
            'TableName' => 'incming',
            'Item' => array(
                'clicid' => array(
                    'S' => $clicid
                ),
                'ip' => array(
                    'S' => $ip
                ),
                'country' => array(
                    'S' => $country
                ),
                'agent' => array(
                    'S' => $agent
                ),
                'time' => array(
                    'N' => $time
                ),
                'rpm' => array(
                    'N' => $rpmselected
                ),
                'pubrpm' => array(
                    'N' => $revenu
                ),
                'soldto' => array(
                    'S' => $idselectedcampaign
                ),
                'campaign' => array(
                    'S' => $campaign
                ),
                'urlsent' => array(
                    'S' => $urlselected
                ),
                'platform' => array(
                    'S' => $platform
                ),
                'browser' => array(
                    'S' => $browser
                )
            )
        ));
        echo "<meta http-equiv=\"refresh\" content=\"0;url=$urlselected\"/>";
        exit;
    } else {
        // Hack pour automatiquement basculer sur la 2 si la 1 a atteint le capping et augmenter le fill rate
        if ($totalcamps > 1) {
            $selectcampaign = rand(2, $totalcamps);
            $rechcomptew2   = "SELECT campaign AS selectedcampaignid from adv_campaigns_positions where country = '$countrysearch' and system='$ciblecomputer$ciblebrowser' and position = '$selectcampaign' limit 1";
            $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
            $data               = mysql_fetch_array($reqcomptew2);
            $idselectedcampaign = $data['idselectedcampaign'];
            //
            $rechcomptew2       = "SELECT url,capping,cpm from adv_campaigns_details where id='$idselectedcampaign' limit 1";
            $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
            $data            = mysql_fetch_array($reqcomptew2);
            $urlselected     = $data['url'];
            $cappingselected = $data['capping'];
            $cpmselected     = $data['cpm'];
            $rpmselected     = $cpmselected / 1000;
            //
            $urlselected     = str_replace("{zone}", "$zone", $urlselected);
            $urlselected     = str_replace("{clicid}", "$clicid", $urlselected);
            $urlselected     = str_replace("{miniclicid}", "$miniclicid", $urlselected);
            $urlselected     = str_replace("{time}", "$time", $urlselected);
            $urlselected     = str_replace("{country}", "$country", $urlselected);
            $urlselected     = str_replace("{cost}", "$rpmselected", $urlselected);
            //
            $rechcomptew2    = "SELECT views from capping where ip='$ip' and campaign='$idselectedcampaign' limit 1";
            $reqcomptew2 = mysql_query($rechcomptew2) or die('Erreur SQL !<br>' . $rechcomptew2 . '<br>' . mysql_error());
            $data           = mysql_fetch_array($reqcomptew2);
            $currentcapping = $data['views'];
            if ($cappingselected == 0) {
                $currentcapping = -1;
            }
            if ($currentcapping <= $cappingselected) {
                $nvviews = $currentcapping + 1;
                try {
                    $dbcloud = new PDO('mysql:dbname=trafficavenue;host=tamemcluster.c2brsqgmxqob.us-east-1.rds.amazonaws.com', 'trafficavenueweb', 'Miami;06082011');
                }
                catch (PDOException $ex) {
                    echo 'Connection failed: ' . $ex->getMessage();
                }
                $sql  = "DELETE FROM capping where ip = '$ip' AND campaign = '$idselectedcampaign'";
                $sql2 = "INSERT INTO capping (ip,campaign,views,updated) VALUES('$ip','$idselectedcampaign', '$nvviews','$time')";
                // use exec() because no results are returned
                $dbcloud->exec($sql);
                $dbcloud->exec($sql2);
                $percent = (($rpmselected / 100) * 40);
                $revenu  = $rpmselected - $percent;
                $result  = $client->putItem(array(
                    'TableName' => 'incming',
                    'Item' => array(
                        'clicid' => array(
                            'S' => $clicid
                        ),
                        'ip' => array(
                            'S' => $ip
                        ),
                        'country' => array(
                            'S' => $country
                        ),
                        'agent' => array(
                            'S' => $agent
                        ),
                        'time' => array(
                            'N' => $time
                        ),
                        'rpm' => array(
                            'N' => $rpmselected
                        ),
                        'pubrpm' => array(
                            'N' => $revenu
                        ),
                        'soldto' => array(
                            'S' => $idselectedcampaign
                        ),
                        'campaign' => array(
                            'S' => $campaign
                        ),
                        'urlsent' => array(
                            'S' => $urlselected
                        ),
                        'platform' => array(
                            'S' => $platform
                        ),
                        'browser' => array(
                            'S' => $browser
                        )
                    )
                ));
                echo "<meta http-equiv=\"refresh\" content=\"0;url=$urlselected\"/>";
                exit;
            }
        }
        
        if ($return_url == "") {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "finfich";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=http://qjs39.totalspeedbrand.com/?noaudio=1&noalert=1&noexit=1&nodl=nodl\"/>";
            exit;
        } else {
            $rpm    = 0;
            $soldto = "Returned NB";
            $reason = "finfich";
            $result = $client->putItem(array(
                'TableName' => 'incming',
                'Item' => array(
                    'clicid' => array(
                        'S' => $clicid
                    ),
                    'ip' => array(
                        'S' => $ip
                    ),
                    'country' => array(
                        'S' => $country
                    ),
                    'agent' => array(
                        'S' => $agent
                    ),
                    'time' => array(
                        'N' => $time
                    ),
                    'rpm' => array(
                        'N' => $rpm
                    ),
                    'soldto' => array(
                        'S' => $soldto
                    ),
                    'reason' => array(
                        'S' => $reason
                    ),
                    'campaign' => array(
                        'S' => $campaign
                    ),
                    'urlsent' => array(
                        'S' => $return_url
                    ),
                    'platform' => array(
                        'S' => $platform
                    ),
                    'browser' => array(
                        'S' => $browser
                    )
                )
            ));
            echo "<meta http-equiv=\"refresh\" content=\"0;url=$return_url\"/>";
            exit;
        }
    }
    mysql_close($link);
    exit;
}
//
?>