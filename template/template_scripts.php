<?php

echo '
    
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \'https://connect.facebook.net/sk_SK/sdk.js#xfbml=1&version=v2.11&appId=185797171969761\';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, \'script\', \'facebook-jssdk\'));</script>

    <script type="text/javascript" src="'.URL.'/assets/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="'.URL.'/assets/js/uikit.min.js"></script>
    <script type="text/javascript" src="'.URL.'/assets/js/uikit-icons.min.js"></script>
';