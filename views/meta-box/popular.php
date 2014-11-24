<?php
/**
 * Custom meta box to display popularity (likes & views).
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

global $post;

$inflated = $array['inflate'];
$social = Database::get_row( Social::$table, 'post_id', $post->ID );

if( !empty( $data[0] ) ) :
	extract( $data[0] );

	echo "<table style='font-size:1.2em; width:100%;'>";
	echo ( $inflated ) ? "<thead><tr><th style='width:30%;'></th><td style='width:23%;'><em><small>Real</small></em></td><td style='width:23%;'><em><small>Infl</small></em></td><td style='width:23%;'><em><small>Total</small></em></td></thead>" : "";
	echo "<tbody>";

	// Display views
	$views_math = ( $inflated ) ? "<td><small>+0</small></td><td><small>= $views</small></td>" : "";
	echo "<tr><th style='text-align:left;'>Views:</th><td><strong>$views</strong></td>$views_math</tr>";

	// Display likes
	$likes_math = ( $inflated ) ? "<td><small>+$infl</small></td><td><small>= " . ( $likes + $infl ) . "</small></td>" : "";
	echo "<tr><th style='text-align:left;'>Likes:</th><td><strong>$likes</strong></td>$likes_math</tr>";

	// Display shares
	if( !empty( $social ) ) :
		extract( $social );

		$total_shares = ( $facebook_shares + $twitter_shares + $google_shares + $pinterest_shares + $linkedin_shares + $reddit_shares );
		$total_infl = ( $inflated ) ? ( $facebook_infl + $twitter_infl + $google_infl + $pinterest_infl + $linkedin_infl + $reddit_infl ) : '';
		$shares_infl = ( $inflated && get_option( 'social_inflated' ) ) ? "<td><small>+$total_infl</small></td><td><small>= " . ( $total_shares + $total_infl ) . "</small></td>" : "";
		echo "<tr><th style='text-align:left;'>Shares:</th><td><strong>$total_shares</strong></td>$shares_infl</tr>";

		echo "</tbody>";
		echo "</table>";
		echo "<hr>";
		echo "<table style='width:100%;'>";
		echo "<tbody>";

		foreach( Social::$share_url as $network => $value ) {
			$network_math = ( get_option( 'social_inflated' ) ) ? '<td style="width:23%;"><small>+' . $social[$network.'_infl'] . '</small></td><td style="width:23%;"><small>= ' . ( $social[$network.'_infl'] + $social[$network.'_shares'] ) . '</small></td>' : '';
			echo '<tr><th style="text-align:left; width:30%;">' . ucwords( $network ) . ':</th><td style="width:23%;"><strong style="font-size:1.2em;">' . $social[$network.'_shares'] . '</strong></td>' . $network_math . '</tr>';
		}

	endif;

	echo "</tbody>";
	echo "</table>";

else :

	echo "<h3>No Data</h3>";

endif;