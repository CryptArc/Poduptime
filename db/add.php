<!-- /* Copyright (c) 2011, David Morley. This file is licensed under the Affero General Public License version 3 or later. See the COPYRIGHT file. */ -->
<?php
$valid=0;
 include('config.php');
if (!$_POST['url']){
  echo "no url given";
 die;
}
if (!$_POST['email']){
  echo "no email given";
 die;
}
if (!$_POST['domain']){
  echo "no pod domain given";
 die;
}

 $dbh = pg_connect("dbname=$pgdb user=$pguser password=$pgpass");
     if (!$dbh) {
         die("Error in connection: " . pg_last_error());
     }
 $sql = "SELECT domain,pingdomurl FROM pods";
 $result = pg_query($dbh, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }
 while ($row = pg_fetch_array($result)) {
if ($row["domain"] == $_POST['domain']) {
echo "domain already exists";die;
}
if ($row["pingdomurl"] == $_POST['url']) {
echo "pingdom report already exists";die;
}
 }

     //curl the header of pod with and without https

        $chss = curl_init();
        curl_setopt($chss, CURLOPT_URL, "https://".$_POST['domain']);
        curl_setopt($chss, CURLOPT_POST, 1);
        curl_setopt($chss, CURLOPT_HEADER, 1);
        curl_setopt($chss, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($chss, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chss, CURLOPT_NOBODY, 1);
        $outputssl = curl_exec($chss);
        curl_close($chss);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$_POST['domain']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        $output = curl_exec($ch);
        curl_close($ch);

if (stristr($outputssl, 'Set-Cookie: _diaspora_session=')) {
  echo "Your pod has ssl and is valid<br>";
  $valid=1;
}
if (stristr($output, 'Set-Cookie: _diaspora_session=')) {
  echo "Your pod does not have ssl but is a valid pod<br>";
  $valid=1;
}

if ($valid=="1") {    
     $pingdomurl = pg_escape_string($_POST['url']);
     $domain = pg_escape_string($_POST['domain']);
     $email = pg_escape_string($_POST['email']);
    
     $sql = "INSERT INTO pods (domain, pingdomurl, email) VALUES('$domain', '$pingdomurl', '$email')";
     $result = pg_query($dbh, $sql);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
     $to = $adminemail;
     $subject = "New pod added to poduptime ";
     $message = "http://podupti.me\n\n Pingdom Url:" . $_POST["url"] . "\n\n Pod:" . $_POST["domain"] . "\n\n";
     $headers = "From: ".$_POST["email"]."\r\nReply-To: ".$_POST["email"]."\r\n";
     @mail( $to, $subject, $message, $headers );    

     echo "Data successfully inserted! Your pod will be reviewed and live on the list soon!";
    
     pg_free_result($result);
    
     pg_close($dbh);
} else {
echo "Could not validate your pod on http or https, check your setup!";
}

?>