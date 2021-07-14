# Instagram Basic Display API - PHP Integration

## Description
This application allows you to download the media from your Instagram profile, through the **Instagram Basic Display API**. The authentication is based on a [Long Live Token](https://developers.facebook.com/docs/instagram-basic-display-api/guides/long-lived-access-tokens/) that you can get using the application itself. The media files will be saved under *./media* by default and the media information will be saved in *./media/info.json*. Starting from it, you can modify the script and classes in order to make an integration between Instagram and you website (WordPress or not). For example you want to populate the gallery on your website based on your Instagram feed. 

## Requirements
* PHP >=7.2
* Composer

## Installation
```
git clone https://github.com/gmaccario/instagram-basic-display-api-php-integration.git
```
Or download the package from [Github](https://github.com/gmaccario/instagram-basic-display-api-php-integration).

## For local env
Vendor folder is not included in the repo, so after cloning the project you must run **composer update -vvv**

## How to
1. Go to the **Create FB app** section down below in this README, and complete all the steps
2. Copy the *Instagram App ID* and the *Instagram App Secret* (Facebook for Developers app page)
3. Open *./config/config.php* and paste then the appId, the appSecret and the redirectUri
4. Open the index page into your browser (or your custom url if you're working in a subfolder):
[http://localhost/](http://localhost/)
5. Go ahead and make the login through Instagram
6. You will be redirected to the Valid OAuth Redirect URIs that you set up before
7. Click Get Media, or click this link:
[http://localhost/?action=get-media](http://localhost/?action=get-media)
8. Open the log under *./logs* and check if you see the messages
9. Check the media files and the info files generated under *./media*

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
10. Set up a Valid OAuth Redirect URIs, Deauthorize Callback URL and Data Deletion Request URL
11. Under User Token Generator, add an Instagram Tester
12. Under Instagram Testers, click Add Instagram Testers
13. Enter the username of the tester and click Submit
14. Open this page https://www.instagram.com/accounts/manage_access/ 
15. Under Tester Invites, accept the invitation
16. Go back to the **How to** section and use the token
17. You are able to test your application now. When you're ready, swith the App Mode to *Live*. 

Note: in order to complete step #17, in your *Facebook for Developers app page* you must enter a valid *Privacy Policy URL* for the Facebook app, the app's icon, the App Purpose use and the category.
