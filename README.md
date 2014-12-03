Интоор Library
==============
"If a Wordpress theme was a cherry then the Интоор Library is the pit."

This library can and should be used as the backbone of your Wordpress theme development.  It provides additional levels of functionality not currently built into the core Wordpress CMS and allows you as a theme developer to quickly extend the Wordpress Admin.

It may be helpful to know that _Интоор_ [ɪn.ˈtɔːr], pronounced _in-tor_, is the Mongolian word for 'Cherry' and is commonly Romanized as _Intoor_.

__NOTE__: _This project is still in its infancy so changes and updates will come fast and often so stay tuned._


Key Features
------------
* Quickly and easily create admin menus, custom post types, custom meta boxes, database tables, and much more with associative arrays
* Email mailing list creation and management
* Popularity tracking including page views, likes, and shares
* Google Analytics integration
* Secure data encryption


Requirements
------------
* [PHP v5.5](http://php.net/downloads.php) or later
* [Mcrypt](http://php.net/manual/en/book.mcrypt.php)
* [Wordpress 4.0](https://wordpress.org/download/) or later


Installation
------------
1. Navigate to your Wordpress theme directory and create a new sub-directory called 'lib'.  _Note: You can call the new sub-directory anything you want just be sure to complete installation step 5._

	```bash
	$ cd yoursite.com/wp-content/themes/yourtheme/
	$ mkdir lib
	$ cd lib
	```

2. Initialize git and clone this repo into the `lib/` directory you created in step 1.

	```bash
	$ git init
	$ git remote add origin git@github.com:Alekhen/intoor-lib.git
	$ git fetch
	$ git checkout -t origin/master
	```

3. Create a configuration file unique to your project called `config.php` by copying `config-sample.php`.  _Note: Do not change files directly in the Интоор Library other than `config.php` or else you run the risk of major conflicts if you ever want to update the library in the future._

	```bash
	$ cp config-sample.php config.php
	```

4. Update the configuration file you created in step 3 with unique phrases to keep your data secure.  Feel free to use keys generated by Wordpress.org's secret-key service if you'd like: [https://api.wordpress.org/secret-key/1.1/salt/](https://api.wordpress.org/secret-key/1.1/salt/)

	```php
	define( 'INTOOR_API_KEY', 'put your unique phrase here' );
	define( 'INTOOR_MAIL_KEY', 'put your unique phrase here' );
	```

5. [_Optional_] If you called the Интоор Library directory something other than 'lib', make sure to update the `INTOOR_DIR_NAME` definition in the `config.php` file you created in step 3.

	```php
	define( 'INTOOR_DIR_NAME', 'lib' );
	```

6. Connect the Интоор Library to your Wordpress theme by including `lib/config.php` at the beginning of your theme's `functions.php` file.

	```php
	require_once dirname( __FILE__ ) . "/lib/config.php";
	```

7. [_Optional_] Add the `lib/` directory to your theme's `.gitignore` file.  This prevents a copy of the Интоор Library from being stored in your theme's git repo.


Updating Instructions
---------------------
1. Navigate to the `lib/` directory inside your Wordpress theme and run a git pull to retrieve the updated and modified project files.

	```bash
	$ cd yoursite.com/wp-content/themes/yourtheme/lib/
	$ git pull
	```

2. Update your `lib/config.php` file with the current version.

	```php
	define( 'INTOOR_LIB_VERSION', '1.2' );
	```


Change Log
----------
### v1.2 - Current Version
* Setup and configuration refactor

### v1.1
* PHP v5.5 progressive refactor (dropped support for PHP v5.3)
* Mailing List, Popular, & Social API refactor

### v1.0
* Initial file structure (config, paths, etc)
* Database, encryption, and general function classes that manage interaction with $wpdb
* Admin Menu class to create WP admin menu pages
* Post Type class to create custom post types
* Mailing List - WP admin menu, mailing list API, HTML subscribe & unsubscribe emails, CSV generation
* Popular Tracking - Views and likes API
* Social Network Integration


License
-------
Copyright © 2014, [Hazard Media Group LLC](http://hazardmediagroup.com)

* [MIT License](https://github.com/Alekhen/intoor/blob/master/LICENSE)


Development
-----------
* Source hosted at [GitHub](https://github.com/Alekhen/intoor-lib).

#### Author
[Colton James Wiscombe](http://coltonjameswiscombe.com)