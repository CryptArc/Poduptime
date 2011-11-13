<!doctype html><html><head><meta charset="utf-8"><title>Diaspora Pod uptime - Find your new social home</title>
<meta name="keywords" content="diaspora, podupti.me, diasp, diasporg, diasp.org, facebook, open source social, open source facebook, open source social network" />
<meta name="description" content="Diaspora Pod Live Status. Find a Diaspora pod to sign up for, rate pods, find one close to you!" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script> 
<script type="text/javascript" src="http://c807316.r16.cf2.rackcdn.com/jquery.tablesorter.min.js"></script> 
<script type="text/javascript" src="http://c807316.r16.cf2.rackcdn.com/jquery.loading.1.6.4.min.js"></script> 
<script type="text/javascript" src="http://c807316.r16.cf2.rackcdn.com/jquery.tipsy.js"></script>
<script type="text/javascript" src="http://c807316.r16.cf2.rackcdn.com/podup.js"></script>
<script type="text/javascript" src="http://c807316.r16.cf2.rackcdn.com/facebox.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script src="OpenLayers.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">
<link rel="stylesheet" href="http://c807316.r16.cf2.rackcdn.com/newstyle.css" />
<link rel="stylesheet" href="http://c807316.r16.cf2.rackcdn.com/facebox.css" />

<?php include("vendor/Mobile_Detect.php");$detect = new Mobile_Detect();if ($detect->isMobile()) {echo '<link rel="stylesheet" href="http://c807316.r16.cf2.rackcdn.com/mobile.css" /><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';} ?>
<script type="text/javascript">
(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.async = true;
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();
</script></head>
<body>
  <header>
    <div class="page-header">
      <div class="row">
        <div class="span6">
          <h2 id="title">
          DIASPORA* POD UPTIME
          </h2>
        </div>
      <div class="span3" style="margin-top:8px;">
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=davidmmorley"></script>
<!-- AddThis Button END -->
      </div>
      <div class="span4" style="margin-top:8px;">
      <a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://podupti.me"></a>
      </div>
      <div class="span2" style="margin-top:8px;">
<a onClick="map();">Show Map View</a>
      </div>
      <div class="span2" style="margin-top:8px;">
<a onClick="nomap();">Show Table View</a>
      </div>
    </div>
  </div>
</div>
  </header>
  <div class="container-fluid">
    <div class="sidebar"> 
      <div class="adsense"><script type="text/javascript">
      <!-- 
      google_ad_client = "ca-pub-3662181805557062"; 
      /* podup2 */ 
      google_ad_slot = "3334221511"; 
      google_ad_width = 200; 
      google_ad_height = 200; 
      //--> 
      </script> 
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script></div>
      <a href="https://market.android.com/details?id=appinventor.ai_david_morley.DiasporaPoduptime"><img src="http://c807316.r16.cf2.rackcdn.com/android-dude128.png"></a>
    </div>
    <div class="content">
    <div id="map" style="width:80%;height:500px;position:absolute;display:none"></div>
      <div id="results">
        <?php include("show.php"); ?>
      </div>
      <div id="add">
        Pod Host? <u style="cursor: pointer; cursor: hand;">Click here</u> to manage your listing.<br>
        Our source is on <a href="https://github.com/diasporg/Poduptime">GitHub</a><br><br>
        Some pods are <a href="http://podupti.me/?hidden=true">Hidden</a> That have too many issues.<br><br>
      </div>
      <div id="howto" style="display:none; margin-left:50px">
        <br>
        Want your pod listed?<br>
        Its easy start monitoring on your pod with a free <a href="http://www.pingdom.com" target="new">www.pingdom.com</a> account.<br>
        <br>Make a public report public and then enter your URL below (note its the one you view after you goto it)<br><br>
        Pingdom shows me http://stats.pingdom.com/b4gasnh1c176 when I click it to goes to http://stats.pingdom.com/b4gasnh1c176/240588
        <br> So URL should look very close to this: http://stats.pingdom.com/b4gasnh1c176/240588<br>
        <br><form action="db/add.php" method="post">
        Stats URL:<input type="text" name="url" class="xlarge span8" placeholder="http://stats.pingdom.com/b4gasnh1c176/240588"><br>
        Pod domainname:<input type="text" name="domain" class="xlarge span4" placeholder="domain.com"><br>
        Your Email:<input type="text" name="email" class="xlarge span4" placeholder="user@domain.com"><br>
        <input type="submit" value="submit">
        </form>
        <br>Is your pod missing? If the server can not get a diaspora session its on the hidden list <a href="http://podupti.me/?hidden=true">Show</a>. This
is mostly because of selfsigned or openca certs, if you need a free ssl cert get one from startssl.com.
        <br>Need help? <a href="http://frodointernet.com/support">Support</a>
        <br>
      </div>
<script type="text/javascript">
var clicky_site_ids = clicky_site_ids || [];
clicky_site_ids.push(66500192);
(function() {
  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.async = true;
  s.src = '//static.getclicky.com/js';
  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
})();
</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66500192ns.gif" /></p></noscript>
      <script type="text/javascript">
      /* <![CDATA[ */
          (function() {
              var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
              s.type = 'text/javascript';
              s.async = true;
              s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
              t.parentNode.insertBefore(s, t);
          })();
      /* ]]> */
      </script>
    </div>
  </div>
</body>
</html>
