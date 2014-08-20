Интоор Library
==============
A Cherry Flavored Wordpress Theme Library


Installation
------------
1. Navigate to your wordpress theme's directory and create a new directory called `lib`.  You can call the new directory anything you want just be sure to complete installation step 5.

	```bash
	$ cd yoursite.com/wp-content/themes/yourtheme/
	$ mkdir lib
	$ cd lib
	```

2. Initialize git and clone this repo into the new `lib` directory.

	```bash
	$ git init
	$ git remote add origin git@github.com:Alekhen/intoor-lib.git
	$ git fetch
	$ git checkout -t origin/master
	```

3. Include the Интоор Library config file in `yourtheme/functions.php`.

	```php
	require_once dirname( __FILE__ ) . "/lib/config.php";
	```

4. _(Optional)_ Add `lib` directory to your `.gitignore` file if you don't want the Интоор Library to be stored in your theme repo if you're using git versioning to manage your theme development.

5. _(Optional)_ If you called your new directory something other than `lib` make sure to update the INTOOR_DIR_NAME definition in `config.php`.

	```php
	define( 'INTOOR_DIR_NAME', 'lib' );
	```


Usage Documentation
-------------------

### General Usage

### WP Custom Post Type

### WP Custom Admin Menu Pages

### WP Custom Meta Boxes

### Social Networks
The Интоор Library gives you the ability to adding support for social networks

#### Supported Networks
The following social networks are supported the Интоор Library:

* [Facebook](http://facebook.com)
* [Twitter](http://twitter.com)
* [Google Plus](http://plus.google.com)
* [Pinterest](http://pinterest.com)
* [Instagram](http://instagram.com)
* [YouTube](http://youtube.com)
* [LinkedIn](http://linkedin.com)
* [Tumblr](http://tumblr.com)
* [Vine](http://vine.co)
* [Vimeo](http://vimeo.com)
* [Soundcloud](http://soundcloud.com)
* [Flickr](http://flickr.com)
* [GitHub](http://github.com)
* [Behance](http://behance.net)
* [Dribbble](http://dribbble.com)
* [DeviantART](http://deviantart.com)
* [Yelp](http://yelp.com)
* [Foursquare](http://foursquare.com)
* [Meetup](http://meetup.com)
* [Myspace](http://myspace.com)
* [Reddit](http://reddit.com)
* [Weibo](http://weibo.com)
* [Renren](http://renren.com)

The following social networks are supported by the Интоор Library for sharing:

* [Facebook](http://facebook.com)
* [Twitter](http://twitter.com)
* [Google Plus](http://plus.google.com)
* [Pinterest](http://pinterest.com)
* [LinkedIn](http://linkedin.com)
* [Reddit](http://reddit.com)

#### Methods
The following methods are available to be used throughout your theme.



##### Social Media Icon
This method will include the social media icon matching the key.

###### Usage
```php
<?php Social::social_media_icon( $key ); ?>
<?php Social::get_social_media_icon( $key ); ?>
```

###### Parameters
* __$key__



##### Social Media URL
This method will ...

###### Usage
```php
<?php Social::social_media_url( $key ); ?>
<?php Social::get_social_media_url( $key ); ?>
```

###### Parameters
* __$key__



##### Social Media Button
This method will...

###### Usage
```php
<?php Social::social_media_button( $key, $class ); ?>
<?php Social::get_social_media_button( $key, $class ); ?>
```

###### Parameters
* __$key__
* __$class__



##### Social Media Share URL
This method will...

###### Usage
```php
<?php Social::social_media_share_url( $key, $post_id ); ?>
<?php Social::get_social_media_share_url( $key, $post_id ); ?>
```

###### Parameters
* __$key__
* __$post_id__



##### Social Media Share Count
This method will...

###### Usage
```php
<?php Social::social_media_share_count( $key, $post_id ); ?>
<?php Social::get_social_media_share_count( $key, $post_id ); ?>
```

###### Parameters
* __$key__
* __$post_id__



##### Social Media Share Button
This method will...

###### Usage
```php
<?php Social::social_media_share_button( $key, $post_id, $show_count, $icon_left ); ?>
<?php Social::get_social_media_share_button( $key, $post_id, $show_count, $icon_left ); ?>
```

###### Parameters
* __$key__
* __$post_id__
* __$show_count__
* __$icon_left__



##### Social Media Share Buttons
This method will...

###### Usage
```php
<?php Social::social_media_share_buttons( $key, $post_id, $show_count, $icon_left ); ?>
<?php Social::get_social_media_share_buttons( $key, $post_id, $show_count, $icon_left ); ?>
```

###### Parameters
* __$key__
* __$post_id__
* __$show_count__
* __$icon_left__


#### API

##### Parameters
* __action__
* __post_id__
* __key__

##### Response







### Mailing List

#### Methods
The following methods are available to be used throughout your theme.

##### Form
This method...

###### Usage
```php
<?php Mailing_List::form( $desc, $desc_tag, $template ); ?>
<?php Mailing_List::get_form( $desc, $desc_tag, $template ); ?>
```

###### Parameters
* __$desc__
* __$desc_tag__
* __$template__


#### API
##### Parameters
* __action__
* __email__
* __Response__







### Popular Tracking

#### Methods
The following methods are available to be used throughout your theme.

##### Likes
This method...

###### Usage
```php
<?php Popular::likes( $infl ); ?>
<?php Popular::get_likes( $infl ); ?>
```

###### Parameters
* __$infl__



##### popular( $count, $inc_views, $inc_likes, $random, $offset );
This method...

###### Usage
```php
<?php Popular::popular( $count, $inc_views, $inc_likes, $random, $offset ); ?>
<?php Popular::get_popular( $count, $inc_views, $inc_likes, $random, $offset ); ?>
```

###### Parameters
* __$count__
* __$inc_views__
* __$inc_likes__
* __$random__
* __$offset__


#### API

##### Parameters
* __action__
* __post_id__

##### Response

### Database Class

### Functions Class


Change Log
----------
### v1.0 - Current Version
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