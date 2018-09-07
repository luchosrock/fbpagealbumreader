
# How to get started

- Create a Facebook app (https://developers.facebook.com)
- Create a _long lived_ Page Access Token (check out https://medium.com/@Jenananthan/how-to-create-non-expiry-facebook-page-token-6505c642d0b1 for more details)
- Get the Id of the Facebook Page you want to read from (if you don't know it, you can check https://findmyfbid.com/)

# Configuration

Find the `$fb_access` variable in the `config.php` file and set the fields You've got from your Facebook Developers Page

``` php
$fb_access = array(
'app_id' => '<facebook-app-id>',
'app_secret' => '<facebook-app-secret>',
'access_token' => '<page-token>'
);
```

Set the page id 

``` php
$pageId = '<target-page-id>';
```

# Start

``` bash
$ php -S localhost:8000 
```