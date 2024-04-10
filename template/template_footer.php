<?php

echo '

<div class="uk-position-bottom-right uk-position-fixed uk-position-medium btn-top" id="topbtn"><a href="#" uk-totop uk-scroll></a></div>

<div class="footer">
    <div class="uk-container" style="max-width: 1000px;">
        <div uk-grid>
            <div class="uk-width-auto@m">
                <h3>ricsi.<b>system</b></h3>
                <small class="uk-margin-small-left">my<span>gaming</span>community</small>
            </div>
            <div class="uk-width-auto@m gray uk-margin-top">pallax.systems © 2018 all right reserved</div>
            <div class="uk-width-expand@m gray uk-text-right uk-margin-top">Webdizajn : tadixis</div>
        </div>
    </div>
</div>
<div class="footer-driver"></div>

<script type="text/javascript">
// When the user scrolls down 600px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
        document.getElementById("topbtn").style.display = "block";
    } else {
        document.getElementById("topbtn").style.display = "none";
    }
}
</script>

';