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


Usage & Documentation
---------------------
The following documentation is provided as a users manual for the Интоор Library.

### General Usage

### WP Custom Post Type
The `Post_Type` class included in the Интоор Library gives you the ability to quickly add custom post types to your Wordpress theme.

#### Usage
```php
<?php
$args = array(
	'post_type' => 'products',
	'name_singular' => 'Product',
	'name_plural' => 'Products',
	'namespace' => 'wp',
	'heirarchial' => false,
	'supports' => array( 'title', 'editor' ),
	'taxonomies' => array(),
	'public' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'menu_icon' => 'dashicons-cart',
	'menu_position' => 5,
	'show_in_nav_menus' => true,
	'publicly_queryable' => true,
	'exclude_from_search' => false,
	'has_archive' => true,
	'query_var' => true,
	'can_export' => true,
	'rewrite' => true,
	'capability_type' => 'post'
);
new Post_Type( $args );
?>
```

#### Parameters
* __post_type__: (_string_) (_required_) Name of the custom post type you want to create.  Additionally, this value will be used as the slug for the archive page (__max. 20 characters, can not contain capital letters or spaces__) -- Default: None

* __name_singular__: (_string_) (_required_) Singular display name of the post type (_eg. Book_) -- Default: None

* __name_plural__: (_string_) (_required_) Plural display name of the post type (_eg. Books_) -- Default: None

* __namespace__: (_string_) (_optional_) Namespace declaration (_used for translation_) -- Default: wp

* __heirarchial__: (_boolean_) (_optional_) Create a hierarchy allowing parent and child posts within the custom post type -- Default: false

* __supports__: (_array_) (_optional_) Wordpress meta boxes to include on the custom post type post edit pages.  Allowed options: title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes, post-formats -- Default: array( 'title', 'editor' )

* __taxonomies__: (_array_) (_optional_) Registered taxonomies -- Default: array()

* __public__: (_boolean_) (_optional_) Should the post type be displayed outwardly to general users or is it only to be displayed in the Wordpress admin -- Default: true

* __show_ui__: (_boolean_) (_optional_) Display a user interface for the post type -- Default: true

* __show_in_menu__: (_boolean_) (_optional_) Display the post type in the Wordpress admin sidebar nav menu -- Default: true

* __menu_icon__: (_string_) (_optional_) Dashicons icon name - select from [http://melchoyce.github.io/dashicons/](http://melchoyce.github.io/dashicons/) -- Default: NULL (_defaults to push pin_)

* __menu_position__: (_number_) (_optional_) The location of the custom post type in the Wordpress admin sidebar nav menu (_5 below Posts, 10 below Media, 15 below Links, 20 below Pages, 25 below comments, 60 below first separator, 65 below Plugins, 70 below Users, 75 below Tools, 80 below Settings, 100 below second separator_) -- Default: NULL

* __show_in_nav_menus__: (_boolean_) (_optional_) Allows the post type to be shown in outwardly displayed navigation menus -- Default: true

* __publicly_queryable__: (_boolean_) (_optional_) The front end code can query this post type and display its posts -- Default: true

* __exclude_from_search__: (_boolean_) (_optional_) Should posts from the custom post type show up in searches performed by users on the front end -- Default: false

* __has_archive__: (_boolean_) (_optional_) Enable/disable the archive page for the custom post type -- Default: true

* __query_var__: (_boolean_) (_optional_) Sets the query variable for the custom post type (_true - uses post type, false, custom string_) -- Default: true

* __can_export__: (_boolean_) (_optional_) The custom post type can be exported -- Default: true

* __rewrite__: (_boolean_) (_optional_) Custom post type posts can be rewritten -- Default: true

* __capability_type__: (_string_) (_optional_) Set the functionality of the custom post type similar to either _'post'_ or _'page'_ -- Default: post


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

#### Usage
```php
<?php
$args = array(
	'meta_box' => true,
	'post_type' => array( 'post' ),
	'inflate' => false,
	'infl_range' => 'mid',
	'infl_min' => 10,
	'infl_max' => 50
);
new Social( $args );
?>
```

#### Parameters
* __meta_box__: (_boolean_) (_optional_) Display the social share custom meta box in the Wordpress admin -- Default: true

* __post_type__: (_array_) (_optional_) Type of screen(s) on which to display the social share custom meta box in the Wordpress admin (_post, page, etc._) -- Default: array( 'post' )

* __inflate__: (_boolean_) (_optional_) Artificially inflate the initial 'share' count of each social network -- Default: false

* __infl_range__: (_string_) (_optional_) Range of inflated numbers to be auto generated (_low_ 0-10, _mid_ 10-50, _high_ 50-100, _ultra_ 100-500, _custom_) -- Default: mid

* __infl_min__: (_number_) (_optional_) Custom inflation range min number -- Default: 10

* __infl_max__: (_number_) (_optional_) Custom inflation range max number -- Default: 50


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