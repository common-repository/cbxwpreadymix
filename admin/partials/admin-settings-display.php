<?php
	/**
	 * Provide a settings view for the plugin
	 *
	 * This file is used to markup the public-facing aspects of the plugin.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    cbxwpreadymix
	 * @subpackage cbxwpreadymix/admin/partials
	 */
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e( 'CBX WP Readymix: Setting', 'cbxwpreadymix' ); ?></h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
							<?php
								$this->settings_api->show_navigation();
								$this->settings_api->show_forms();
							?>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                </div> <!-- .meta-box-sortables .ui-sortable -->
            </div> <!-- post-body-content -->
            <!-- include sidebar -->
			<?php include( 'sidebar.php' ); ?>
        </div> <!-- #post-body .metabox-holder .columns-2 --><br class="clear">
    </div> <!-- #poststuff -->
</div> <!-- .wrap -->

<script type="text/javascript">

    jQuery(document).ready(function ($) {
        //if need any js code here
    });

</script>
