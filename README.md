Интоор Library
==============
A Cherry Flavored Wordpress Theme Library


Installation
------------
1. Navigate to your wordpress theme's directory and create a new directory called 'lib'

	```
	cd yoursite.com/wp-content/themes/yourtheme/
	mkdir lib
	cd lib
	```

2. Initialize git and clone this repo into the new 'lib' directory

	```
	git init
	git remote add origin git@github.com:Alekhen/intoor-lib.git
	git fetch
	git checkout -t origin/master
	```

3. Include the Intoor Lib config file in yourtheme/functions.php

	```
	require_once dirname( __FILE__ ) . "/lib/config.php";
	```


Change Log
----------
### v1.0 - Current Version
* Initial file structure (config, paths, etc)
* Database, encryption, and general function classes that manage interaction with $wpdb
* Admin Menu class to create WP admin menu pages
* Post Type class to create custom post types
* Mailing List - WP admin menu, mailing list API, HTML subscribe & unsubscribe emails, CSV generation
* Popular Tracking - Views and likes API


License
-------
Copyright © 2014, [Hazard Media Group LLC](http://hazardmediagroup.com)

* [MIT License](https://github.com/Alekhen/intoor/blob/master/LICENSE)


Development
-----------
* Source hosted at [GitHub](https://github.com/Alekhen/intoor).

#### Author
[Colton James Wiscombe](http://coltonjameswiscombe.com)