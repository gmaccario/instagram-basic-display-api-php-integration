# Instagram Basic Display API - PHP Integration

## Description
This package is a starter application that allows you to download the media from your Instagram profile, through the **Instagram Basic Display API**. The authentication is based on a [Long Live Token](https://developers.facebook.com/docs/instagram-basic-display-api/guides/long-lived-access-tokens/) that you can get from your [Facebook Apps page](https://developers.facebook.com/apps/). The media files will be saved under ./media by default and the media information will be saved in ./media/info.json
Starting from it, you can modify the script and classes in order to make an integration between Instagram and you website (WordPress or not). For example you want to populate the gallery on your website based on your Instagram feed. 

## Requirements
* PHP >=7.2
* Composer

## Installation
```
git clone https://github.com/gmaccario/instagram-basic-display-api-php-integration.git
```

## For local env
Vendor folder is not included in the repo, so after cloning the project run **composer update -vvv**

## How to
1. Go to the **Create FB app** section of this README file, get your token and open the following page into your browser (replace **<your-token>** with your actual token and **localhost** with your actual domain:
[http://localhost/ig-integration/?long-lived-token=<your-token>](http://localhost/ig-integration/?long-lived-token=<your-token>)
2. Open the log under ./logs and check if you see the message:
**Success! Please check your new token in token.json**
3. If yes, open the following page into your browser:
[http://localhost/ig-integration/?action=get-media](http://localhost/ig-integration/?action=get-media)
4. Open the log under ./logs and check if you see the message:
**Found ## media**
5. Check the media files under ./media
6. Just open the config under ./config and make your changes

## Create FB app
1. Go to this page https://developers.facebook.com/apps/
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
15. Back to your Facebook Apps page, under Instagram Basic API -> Basic Display
16. Click on Generate Token, then click Allow
17. Copy the generated token
18. Go back to the **How to** section and use the token
19. That's it!
