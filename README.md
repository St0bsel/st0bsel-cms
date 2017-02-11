handles#Stöbsel CMS README
##Content
* [installation](#installation)
* [get-started](#get-started)
* [files](#files)
  * [structure](#structure)
* [HTML](#html)
  * [IDs](#ids)
    * [navigation](#navigation)
    * [main](#main)
    * [login](#login)
  * [Classes](#classes)
    * [navigation](#navigation)
    * [general](#general)
* [CSS](#css)
  * [variables](#variables)
* [JS](#js)
  * [functions](#functions)
* [PHP](#php)
  * [files](#files)
    * [login.php](#login.php)
      * [functions](#functions)
    * [sql_login.php](#sql_login.php)

## installation
1.  upload all required files to your web-server
2.  create the database with the code provided in `need.sql`
3.  edit the following files:

|file|Description|part|function|
|-|:-|:-|:-|
|`settings.json`|navigation|define here your navpoints like: `["link (href)", "name", "HTML ID"]`|
|`php/sql_login.php`|used for sql connection|`$dbname`|database name|
|||`$dbhost`|database host|
|||`$dbuser`|database user|
|||`$dbpass`|database user password|
|`css/stylesheet.css`|contains styleinformations|`--color_background`|background color|
|||`--color_background_dark`|background color for second layers|
|||`--color_1`|accent color 1|
|||`--color_2`|accent color 2|

##get-started
1. save pages to `include/filename` and add page to navigation bar in `settings.json`.
2. for custom JS use `js/custom.js`.
3. for custom CSS use `css/custom.css`.
4. edit site metadata in `index.html`. Backup this data maybe you need it after CMS update.

##files
###structure

##HTML
###IDs
####navigation
|ID|Description|
|-|:-|
|`navbar_complete`|DIV ID for whole Navigation|
|`navinfo_1`|contains everything shown on the upper left side (logo, name, Menu Button for mobile)|
|`navinfo_1_img`||
|`navinfo_1_h1`||
|`navinfo_2`|contains all nav points|
|`nav_open`|DIV used in responsive design to open menu|
|`nav_close`||
|`navnav_1`|NAV tag used in navigation|
|`navul_1`|UL tag in NAV tag to display navigation points|
|`open_login`|ID for button to open login DIV|

####main
|ID|Description|
|-|:-|
|`main`|everything content related will be placed here due JS|
|`main_content`||

####login
|ID|Description|
|-|:-|
|`login_div`|complete div which is shown or hide|
|`login_div_layer_1`|contains everything to login (usernamefield, passwordfield, etc)|
|`login_div_layer_2`|show data after successful login|
|`lgoin_username`|field for username|
|`lgoin_password`|field for password|
|`submit_login`|to run login function|
|`submit_register`|to redirect to register page|
|`close_login_1`|close login on layer 1|
|`close_login_2`|close login on layer 2|
|`open_userprofile`|to redirect to userprofile page|
|`submit_logout`|to run logout function|


###Classes
####navigation
|Class|Description|
|-|:-|
|`navbar`||
|`navinfo`||
|`navigation_nav`||
|`navigation_ul`||
|`navpos`||

####general
|Class|Description|
|-|:-|
|`button`||
|`input_text`||
|`close_button`||

##CSS
###variables
|variable|Description|
|-|:-|
|`color_1`|used for background colors|
|`color_2`|accent color 1|
|`color_3`|accent color 2|

##JS
###funcitons
````javascript
login(username, password, f, newpassword, usermail, callback);
````
This function makes an ajax call to do login stuff on the server side.

|variable|Description|
|-|:-|
|`username`||
|`password`||
|`f`|funciton which is called in PHP show "php/login.php" for more info|
|`newpassword`||
|`usermail`||
|`callback`|called when function login finished running|
````javascript
loginfunction();
````
This function handels showing/hiding divs, call login function when login clicked, etc.
````javascript
loadnav();
````
loads navigation bar.
````javascript
get(name);
````
Get value from URL parameter called `name`.
````javascript
get_settings(object_name, callback);
````
|variable|Description|
|-|:-|
|object_name|which part of settings file to get|
|callback|function is called after finished reading file, first variable passed wiht the callback function is the required result|
##PHP
###files
####lgoin.php
#####functions
````php
sec_session_start();
````
start secure session for logged in users.
````php
login();
````
check userdata against database and if ok log user in.
````php
checklogin();
````
check if user is logged in.
````php
logout();
````
log user out -> clear session.
````php
register();
````
register a new user.
````php
changepassword();
````
change userpassword.
````php
getuserdata();
````
return userdata in JSON string.
####sql_login.php
