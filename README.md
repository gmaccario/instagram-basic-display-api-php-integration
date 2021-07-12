# IG MEDIA SCRAPER (via APIs) - HOW TO

## Create FB app
1. Go here https://developers.facebook.com/apps/
2. Click "Create App" button
3. Choose the right app type for you (Consumer for example), then click Continue
4. Fill the values for: App Display Name, App Contact Email, App Purpose, then click on Save
5. In "Add Products to Your App" choose "Instagram Graph API"
6. Under Settings, click Add Platform and Select the right Platform for you
7. Set up Privacy Policy URL, User Data Deletion URL, Terms of Service URL and the Site URL
8. Open Instagram Basic API -> Basic Display
9. Click Create New Instagram App
10. Under User Token Generator, add an Instagram Tester
11. Under Instagram Testers, click Add Instagram Testers
12. Enter the username of the tester and click Submit
13. Open this page https://www.instagram.com/accounts/manage_access/ 
14. Under Tester Invites, accept the invitation
15. Back to your Facebook App, under Instagram Basic API -> Basic Display
16. Click on Generate Token, then click Allow
17. Copy the generated token
18. Put the generated token into igfeed/igcron.php as value of the $longLivedToken variable
19. Launch igfeed/igcron.php
20. Set up the right credentials in the config.php (Instagram App ID and Instagram App Secret)
21. Set up $debug to false in config.php
22. When ready, set up a cron job to /igfeed/ig-get-media.php
23. Use the stored images where you want in your system
24. That's it!

## Requirements
PHP >=7.2

## For local env
1. Vendor folder is not included in the repo, run *composer update -vvv* on local
