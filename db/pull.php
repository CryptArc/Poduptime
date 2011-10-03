#!/usr/bin/php
<?php
//* Copyright (c) 2011, David Morley. This file is licensed under the Affero General Public License version 3 or later. See the COPYRIGHT file. */
 include('config.php');
 $dbh = pg_connect("dbname=$pgdb user=$pguser password=$pgpass");
 $dbh2 = pg_connect("dbname=$pgdb user=$pguser password=$pgpass"); 
    if (!$dbh) {
         die("Error in connection: " . pg_last_error());
     }
//foreach pod check it and update db    
 if ($_GET['domain']) {$domain=$_GET['domain'];$sql = "SELECT domain,pingdomurl,score FROM pods WHERE domain = '$domain'";$sleep="0";} 
 else {$sql = "SELECT domain,pingdomurl,score FROM pods";$sleep="18";}

 $result = pg_query($dbh, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }
 while ($row = pg_fetch_all($result)) {
 $numrows = pg_num_rows($result);
 for ($i = 0; $i < $numrows; $i++) {
     $domain =  $row[$i]['domain'];
     $score = $row[$i]['score'];

//get ratings
 $userrate=0;$adminrate=0;
 $sqlforr = "SELECT * FROM rating_comments WHERE domain = '$domain'";
 $ratings = pg_query($dbh, $sqlforr);
 if (!$ratings) {
     die("Error in SQL query: " . pg_last_error());
 }
 $numratings = pg_num_rows($ratings);
 while($myrow = pg_fetch_assoc($ratings)) {
   if ($myrow['admin'] == 0) {
     $userratingavg[] = $myrow['rating'];$userrate++;
   } elseif ($myrow['admin'] == 1) {
     $adminratingavg[] = $myrow['rating'];$adminrate++;
   } 
 }
$userrating = array_sum($userratingavg) / $userrate;
$adminrating = array_sum($adminratingavg) / $adminrate;
echo $domain."\n";
echo $userrating."\n";
echo $adminrating."\n";

if (!$userrating) {$userrating=8;}
if ($userrating > 10) {$userrating=10;}
if (!$adminrating) {$adminrating=8;}
if ($adminrating > 10) {$adminrating=10;}
     pg_free_result($ratings);
echo $userrating."\n";
echo $adminrating."\n";

     //curl the header of pod with and without https

        $chss = curl_init();
        curl_setopt($chss, CURLOPT_URL, "https://".$domain); 
        curl_setopt($chss, CURLOPT_POST, 1);
        curl_setopt($chss, CURLOPT_HEADER, 1);
        curl_setopt($chss, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chss, CURLOPT_NOBODY, 1);
        $outputssl = curl_exec($chss);      
        curl_close($chss);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$domain);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        $output = curl_exec($ch);
        curl_close($ch);

if (stristr($outputssl, 'Set-Cookie: _diaspora_session=')) {
//parse header data
$secure="true";
//$hidden="no";
$score = $score +1;
preg_match('/X-Git-Update: (.*?)\n/',$outputssl,$xgitdate);
$gitdate = trim($xgitdate[1]);
//$gitdate = strtotime($gitdate);
preg_match('/X-Git-Revision: (.*?)\n/',$outputssl,$xgitrev);
$gitrev = trim($xgitrev[1]);
preg_match('/X-Runtime: (.*?)\n/',$outputssl,$xruntime);
$runtime = trim($xruntime[1]);
preg_match('/Server: (.*?)\n/',$outputssl,$xserver);
$server = trim($xserver[1]);
preg_match('/Content-Encoding: (.*?)\n/',$outputssl,$xencoding);
$encoding = trim($xencoding[1]);

} elseif (stristr($output, 'Set-Cookie: _diaspora_session=')) {
"not";$secure="false";
//$hidden="no";
$score = $score +1;
//parse header data
preg_match('/X-Git-Update: (.*?)\n/',$output,$xgitdate);
$gitdate = trim($xgitdate[1]);
//$gitdate = strtotime($gitdate);
preg_match('/X-Git-Revision: (.*?)\n/',$output,$xgitrev);
$gitrev = trim($xgitrev[1]);
preg_match('/X-Runtime: (.*?)\n/',$output,$xruntime);
$runtime = trim($xruntime[1]);
preg_match('/Server: (.*?)\n/',$output,$xserver);
$server = trim($xserver[1]);
preg_match('/Content-Encoding: (.*?)\n/',$output,$xencoding);
$encoding = trim($xencoding[1]);
} else {
$secure="false";
$score = $score - 1;
//$hidden="yes";
//no diaspora cookie on either, lets set this one as hidden and notify someone its not really a pod
//could also be a ssl pod with a bad cert, I think its ok to call that a dead pod now
}
if (!$gitdate) {
//if a pod is not displaying the git header data its really really really old lets lower your score
//$hidden="yes";
$score = $score - 1;
}
if ($score > 5) {
$hidden = "no";
} else {
$hidden = "yes";
}
// lets cap the scores or you can go too high or too low to never be effected by them
if ($score > 20) {
$score = 20;
} elseif ($score < -20) {
$score = -20;
}

$ip6 = escapeshellcmd('dig +nocmd '.$domain.' aaaa +noall +short');
$ip = escapeshellcmd('dig +nocmd '.$domain.' a +noall +short');
$ip6num = exec($ip6);
$ipnum = exec($ip);
$test = strpos($ip6num, ":");
if ($test === false) {
$ipv6="no";
} else {
$ipv6="yes";
}

//curl the pingdom page 
        $ping = curl_init();
        $thismonth = "/".date("Y")."/".date("m");
        curl_setopt($ping, CURLOPT_URL, $row[$i]['pingdomurl'].$thismonth);
        curl_setopt($ping, CURLOPT_POST, 0);
        curl_setopt($ping, CURLOPT_HEADER, 1);
        curl_setopt($ping, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ping, CURLOPT_NOBODY, 0);
        curl_setopt($ping, CURLOPT_MAXCONNECTS, 5);
        curl_setopt($ping, CURLOPT_FOLLOWLOCATION, true);
        $pingdom = curl_exec($ping);
        curl_close($ping);

//response time
preg_match_all('/<h3>Avg. resp. time this month<\/h3>
<p class="large">(.*?)</',$pingdom,$matcheach);
$responsetime = $matcheach[1][0];

//months monitored
preg_match_all('/<option value=(.*?)</i',$pingdom,$matchdates);
$months = count($matchdates[0]);

//uptime %
preg_match_all('/<h3>Uptime this month<\/h3>
<p class="large">(.*?)</',$pingdom,$matchper);
$uptime = preg_replace("/,/", ".", $matchper[1][0]);

//last check
preg_match_all('/<h3>Last checked<\/h3>
<p>(.*?)</',$pingdom,$matchdate);

$pingdom_timestamp = $matchdate[1][0];
$Date_parts = preg_split("/[\s-]+/", $pingdom_timestamp);
if (strlen($Date_parts[0]) == "2") {
//echo $pingdom_timestamp;
$pingdomdate = $pingdom_timestamp;
}
else {
//$splitdate = explode(" ",$matchdate[1][0]);
//echo $row[$i]['pingdomurl'].$thismonth;
//$newtimestamp = $splitdate[0];
//$matchdate[1][0] = preg_replace("/./", "/", $matchdate[1][0]);
//echo $matchdate[1][0];
//$dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $matchdate[1][0]);
//$dateTime = DateTime::createFromFormat('m.d.Y. H:i:s', $matchdate[1][0]);
//$newunpin = strtotime($dateTime->format('Y-m-d h:i:s a'));
//fuck it so many date formats from pingdom
$pingdomdate = date('Y-m-d H:i:s');
//echo $dateTime->format('Y-m-d h:i:s a');
//$pingdomdate = $splitdate[0];
}

//status
if (strpos($pingdom,"class=\"up\"")) { $live="up"; }
elseif (strpos($pingdom,"class=\"down\"")) { $live="down"; }
elseif (strpos($pingdom,"class=\"paused\"")) { $live="paused";}
else {$live="error";}

//sql it
     $timenow = date('Y-m-d H:i:s');
     $sql = "UPDATE pods SET Hgitdate='$gitdate', Hencoding='$encoding', secure='$secure', hidden='$hidden', Hruntime='$runtime', Hgitref='$gitrev', ip='$ipnum', ipv6='$ipv6', monthsmonitored='$months', uptimelast7='$uptime', status='$live', dateLaststats='$pingdomdate', dateUpdated='$timenow', responsetimelast7='$responsetime', score='$score', adminrating='$adminrating', userrating='$userrating' WHERE domain='$domain'";
     $result = pg_query($dbh, $sql);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
    
     echo "1";


//end foreach
sleep($sleep);
 }
 }   
     pg_free_result($result);
    
     pg_close($dbh);

?>