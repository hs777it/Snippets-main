<?php


// https://wetopi.com/disable-wordpress-cron/
// https://documentation.cpanel.net/display/74Docs/Cron+Jobs

/* 1. Disable wp-cron at the WordPress wp-config.php
after the opening PHP tag <?php enter:
*/

define('DISABLE_WP_CRON', true);

// cPanel 2. cPanel >> Go to Add a New Cron Job, select Common Settings >> Once Per Hour: 


/*  
        /usr/bin/php /home/USERNAME/public_html/wp-cron.php >/dev/null 2>&1
        
        where USERNAME is your cPanel Username and public_html is your website's Document Root and click on Add New Cron Job:
*/


?>

https://www.coralnodes.com/leverage-browser-caching/

1. Enable GZIP Compression
https://kinsta.com/blog/enable-gzip-compression/
https://wpbuffs.com/enable-compression-wordpress-gzip/
https://kinsta.com/blog/enable-gzip-compression/#how-to-check-if-gzip-compression-is-enabled



https://www.giftofspeed.com/gzip-test/
https://www.whatsmyip.org/http-compression-test/

2. Reduce HTTP Requests / Make Fewer HTTP Requests
https://kinsta.com/blog/make-fewer-http-requests/
https://www.coralnodes.com/reduce-server-response-time-ttfb/
1. Remove Unnecessary WordPress Plugins
2. Replace Heavy Plugins With More Lightweight Ones
3. Conditionally Load Scripts That Aren’t Needed Sitewide
4. Remove Unnecessary Images (And Optimize the Rest)
5. Use Lazy Loading for Images and Videos
6. Limit Custom Fonts Use and/or Use System Fonts (Same for Icon Fonts)
7. Disable WordPress Emojis
https://wordpress.org/plugins/disable-emojis/
8. Reduce/Eliminate Third-Party HTTP Requests
9. Combine Images with CSS Sprites
10. Combine CSS and JavaScript Files

Best WordPress Plugins to Make Fewer HTTP Requests
If you’re looking for some “all in one” WordPress plugins to make fewer HTTP requests, we recommend two of the plugins from the tutorial above:

WP Rocket
Perfmatters

