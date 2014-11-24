Интоор Library
==============
A Cherry Flavored Wordpress Theme Library.  This library can and should be used as the backbone of your Wordpress theme development.  It provides additional levels of functionality not currently built into the core Wordpress CMS as well as allows you as a theme developer to quickly extend the Wordpress Admin.  This project is still in its infancy so changes and updates will come often so stay tuned.


Installation
------------
1. Navigate to your Wordpress theme directory and create a new sub-directory called `lib`.  (You can call the new sub-directory anything you want just be sure to complete installation step 5).

	```bash
	$ cd yoursite.com/wp-content/themes/yourtheme/
	$ mkdir lib
	$ cd lib
	```

2. Initialize git and clone this repo into the new `lib/` directory.

	```bash
	$ git init
	$ git remote add origin git@github.com:Alekhen/intoor-lib.git
	$ git fetch
	$ git checkout -t origin/master
	```

3. Create a unique configuration file called `config.php` by copying `config-sample.php`.  _Note: Do not change files within the Интоор Library directory other than `config.php` or else you run the risk of major conflicts if you ever want to update the library in the future._

	```bash
	$ cp config-sample.php config.php
	```

4. Update the configuration file you created in step 3 with unique strings to keep your data secure.

	```php
	define( 'INTOOR_API_KEY', 'put your unique phrase here' );
	define( 'INTOOR_MAIL_KEY', 'put your unique phrase here' );
	```

5. _(Optional)_ If you called the Интоор Library directory something other than 'lib', make sure to update the `INTOOR_DIR_NAME` definition in `config.php`.

	```php
	define( 'INTOOR_DIR_NAME', 'lib' );
	```

6. Connect the Интоор Library to your Wordpress theme by including `lib/config.php` at the beginning of your theme's `functions.php` file.

	```php
	require_once dirname( __FILE__ ) . "/lib/config.php";
	```

7. _(Optional)_ Add the `lib/` directory to your theme's `.gitignore` file.  This prevents a copy of the Интоор Library from being stored in the same git repo as your theme.


Updating Instructions
---------------------
1. Navigate to the `lib/` directory inside your Wordpress theme and run a git pull to retrieve the updated and modified project files.

	```bash
	$ cd yoursite.com/wp-content/themes/yourtheme/lib
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