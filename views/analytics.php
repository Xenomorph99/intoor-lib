<?php
/**
 * Google Analytics code snippet
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

$id = 'google_analytics_';
$data = array();
foreach( $this->settings as $name => $value ) {
	$data[$name] = get_option( $id . $name, $value[0] );
}
extract( $data );

$params = ( $account_id ) ? "ga('create', '$account_id', 'auto');\n" : '';
$params .= ( $enhanced_link_attribution ) ? "ga('require', 'linkid', 'linkid.js');\n" : '';
$params .= ( $display_features ) ? "ga('require', 'displayfeatures');\n" : '';
$params .= ( $account_id ) ? "ga('send', 'pageview');" : '';

?>

<!-- Google Analytics -->

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

<?php echo ( $_SERVER['SERVER_NAME'] == $live_url ) ? $params : ''; ?>

</script>

<!-- END: Google Analytics -->