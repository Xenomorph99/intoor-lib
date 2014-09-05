<?php
/**
 * Custom meta box to display popularity (likes & views).
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

global $post;

$inflated = $array['inflate'];
$social = Database::get_row( Social::$table, 'post_id', $post->ID );

if( !empty( $data[0] ) ) :
	extract( $data[0] );

	// Display views
	echo "<h2>Views: <strong style='font-size:1.2em;'>$views</strong></h2>";

	// Display likes
	$likes_math = ( $inflated ) ? " <small>(+$infl = " . ( $likes + $infl ) . ")</small>" : "";
	echo "<h2>Likes: <strong style='font-size:1.2em;'>$likes</strong>$likes_math</h2>";

	// Display shares
	if( !empty( $social ) ) :
		extract( $social );

		$total_shares = ( $facebook_shares + $twitter_shares + $google_shares + $pinterest_shares + $linkedin_shares + $reddit_shares );
		$total_infl = ( $facebook_infl + $twitter_infl + $google_infl + $pinterest_infl + $linkedin_infl + $reddit_infl );
		$shares_infl = ( get_option( 'social_inflated' ) ) ? " <small>(+$total_infl = " . ( $total_shares + $total_infl ) . ")</small>" : "";
		echo "<h2>Shares: <strong style='font-size:1.2em;'>$total_shares</strong>$shares_infl</h2>";

		echo "<hr>";
		echo "<ol>";

			// Facebook shares
			$facebook_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$facebook_infl = " . ( $facebook_infl + $facebook_shares ) . ")</small>" : "";
			echo "<li>Facebook: <strong style='font-size:1.2em;'>$facebook_shares</strong>$facebook_math</li>";

			// Twitter shares
			$twitter_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$twitter_infl = " . ( $twitter_infl + $twitter_shares ) . ")</small>" : "";
			echo "<li>Twitter: <strong style='font-size:1.2em;'>$twitter_shares</strong>$twitter_math</li>";

			// Google Plus shares
			$google_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$google_infl = " . ( $google_infl + $google_shares ) . ")</small>" : "";
			echo "<li>Google Plus: <strong style='font-size:1.2em;'>$google_shares</strong>$google_math</li>";

			// Pinterest shares
			$pinterest_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$pinterest_infl = " . ( $pinterest_infl + $pinterest_shares ) . ")</small>" : "";
			echo "<li>Pinterest: <strong style='font-size:1.2em;'>$pinterest_shares</strong>$pinterest_math</li>";

			// Linkedin shares
			$linkedin_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$linkedin_infl = " . ( $linkedin_infl + $linkedin_shares ) . ")</small>" : "";
			echo "<li>Linkedin: <strong style='font-size:1.2em;'>$linkedin_shares</strong>$linkedin_math</li>";

			// Reddit shares
			$reddit_math = ( get_option( 'social_inflated' ) ) ? " <small>(+$reddit_infl = " . ( $reddit_infl + $reddit_shares ) . ")</small>" : "";
			echo "<li>Reddit: <strong style='font-size:1.2em;'>$reddit_shares</strong>$reddit_math</li>";

		echo "</ol>";

	endif;

else :

	echo "<p>No Data</p>";

endif;