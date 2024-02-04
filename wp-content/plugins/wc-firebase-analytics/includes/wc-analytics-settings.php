<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Add Analytic Settings */
$msg = '';
if ( isset( $_POST['firebase_analytics_nonce'] ) && wp_verify_nonce(sanitize_key( $_POST['firebase_analytics_nonce'] ), 'appmaker_firebase_analytics' ) ) {
	if ( isset( $_POST['firebase_analytics'] ) ) {
		$analytic_settings = '';
		if ( isset( $_POST['analytic_settings'] ) ) {
			$analytic_settings = $_POST['analytic_settings'] ;
		}	

		update_option( 'wc_analytics_settings', wp_unslash( $analytic_settings ) );

		if ( ! empty( $analytic_settings ) ) {
			$msg = 'Settings are updated successfully!';
		} else {
			$msg = 'Adding Firebase scripts are mandatory!';
		}
	}
}
?>

<h1><?php esc_html_e( 'Enter your firebase scripts below:', 'fbsanwoo' ); ?></h1>
<?php
if ( ! empty( $msg ) ) {
	esc_html_e( $msg, 'fbsanwoo' );
}
?>
<form method="post" name="firebase_analytics" id="firebase_analytics" class="validate" novalidate="novalidate">
   <?php wp_nonce_field( 'appmaker_firebase_analytics', 'firebase_analytics_nonce' ); ?>
	<table class="form-table">
	<tbody>
	<tr class="form-field form-required">
		<th scope="row"><label for="analytic_settings"><?php esc_html_e( 'Firebase Settings', 'fbsanwoo' ); ?> </label></th>
		<td><textarea name="analytic_settings" type="textarea" id="analytic_settings" rows="20"><?php esc_html_e( get_option( 'wc_analytics_settings' ) ); ?></textarea></td>
	</tr>
	</table>
	<p class="submit"><input type="submit" name="firebase_analytics" id="firebase_analytics" class="button button-primary" value="<?php esc_html_e( 'Save settings', 'fbsanwoo' ); ?>"></p>
</form>
</div>
