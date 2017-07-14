<?php
   /*
   Plugin Name: Disable WordPress Dashboard Widgets
   Plugin URI: http://dionrodrigues.com
   Description: Enable and Disable the standard WordPress Dashboard Widgets without any code modification.
   Version: 1.5
   Author: Dion Rodrigues
   Author URI: http://dionrodrigues.com
   License: GPL2+
   */

/* Define Constants */

$siteurl = get_option('siteurl');
define('PLUG_FOLDER', dirname(plugin_basename(__FILE__)));
define('PLUG_URL', $siteurl.'/wp-content/plugins/' . PLUG_FOLDER);
define('FILE_PATH', dirname(__FILE__));
define('DIR_NAME', basename(FILE_PATH));
// this is the table prefix
global $wpdb;
$widg_table_prefix=$wpdb->prefix.'widg_';
define('WIDG_TABLE_PREFIX', $widg_table_prefix);

/* Define Install/Uninstall Routine */

register_activation_hook(__FILE__,'widg_install');
register_deactivation_hook(__FILE__ , 'widg_uninstall' );

/* Install Routine */
function widg_install()
{
    global $wpdb;
    $table = WIDG_TABLE_PREFIX."dashboard";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        field VARCHAR(50) NOT NULL,
        enabled VARCHAR(50) NOT NULL,
        disabled VARCHAR(50) NOT NULL,
		UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
	  /* Populate table  */
    $wpdb->query("INSERT INTO $table(field,enabled,disabled)
        VALUES('quickpress','1','0'),
			 ('incominglinks','1','0'),
			 ('rightnow','1','0'),
			 ('plugins','1','0'),
			 ('recentdrafts','1','0'),
			 ('recentcomments','1','0'),
			 ('primarywp','1','0'),
			 ('secondarywp','1','0')
	");
}
/* Uninstall Routine */
function widg_uninstall()
{
    global $wpdb;
    $table = WIDG_TABLE_PREFIX."dashboard";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);  
}

/* Create Admin Menu */
add_action('admin_menu','widgets_admin_menu');

function widgets_admin_menu() { 
	add_menu_page(
		"Hide Dashboards",
		"Hide Dashboards",
		8,
		__FILE__,
		"widg_settings",
		PLUG_URL."/images/icon.png"
	); 
}

//settings page
function widg_settings() {

    //user role check
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $quickpress = 'quickpress';
    $incominglinks = 'incominglinks';
    $rightnow = 'rightnow';
    $plugins = 'plugins';
    $recentdrafts = 'recentsdrafts';
    $recentcomments = 'recentcomments';
    $primarywp = 'primarywp';
    $secondarywp = 'secondarywp';
	
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name1 = 'quickpress';
    $data_field_name2 = 'incominglinks';
    $data_field_name3 = 'rightnow';
    $data_field_name4 = 'plugins';
    $data_field_name5 = 'recentdrafts';
    $data_field_name6 = 'recentcomments';
    $data_field_name7 = 'primarywp';
    $data_field_name8 = 'secondarywp';

    // Get existing data from WP DB
    $opt_val1 = get_option( $quickpress );
    $opt_val2 = get_option( $incominglinks );
    $opt_val3 = get_option( $rightnow );
    $opt_val4 = get_option( $plugins );
    $opt_val5 = get_option( $recentdrafts );
    $opt_val6 = get_option( $recentcomments );
    $opt_val7 = get_option( $primarywp );
    $opt_val8 = get_option( $secondarywp );

    // See if the user has submitted changes
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        $opt_val1 = $_POST[ $data_field_name1 ];
        $opt_val2 = $_POST[ $data_field_name2 ];
        $opt_val3 = $_POST[ $data_field_name3 ];
        $opt_val4 = $_POST[ $data_field_name4 ];
        $opt_val5 = $_POST[ $data_field_name5 ];
        $opt_val6 = $_POST[ $data_field_name6 ];
        $opt_val7 = $_POST[ $data_field_name7 ];
        $opt_val8 = $_POST[ $data_field_name8 ];

        // Save data in DB
        update_option( $quickpress, $opt_val1 );
        update_option( $incominglinks, $opt_val2 );
        update_option( $rightnow, $opt_val3 );
        update_option( $plugins, $opt_val4 );
        update_option( $recentdrafts, $opt_val5 );
        update_option( $recentcomments, $opt_val6 );
        update_option( $primarywp, $opt_val7 );
        update_option( $secondarywp, $opt_val8 );

?>
<div class="updated"><p><strong><?php _e('Changes Saved.', 'disable-dashboards' ); ?></strong></p></div>
<?php

    }

    // Create Editing Screen

    echo '<div class="wrap">';
    echo "<h2>" . __( 'Hide WordPress DashBoard Widgets', 'disable-dashboards' ) . "</h2>";
	echo "<p>Enter 1 to enable a widget field, and 0 to disable it.</p>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("QuickPress:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name1; ?>" value="<?php echo $opt_val1; ?>" size="20">
</p>

<p><?php _e("Incoming Links:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name2; ?>" value="<?php echo $opt_val2; ?>" size="20">
</p>
<p><?php _e("Right Now:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name3; ?>" value="<?php echo $opt_val3; ?>" size="20">
</p>
<p><?php _e("Plugins:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name4; ?>" value="<?php echo $opt_val4; ?>" size="20">
</p>
<p><?php _e("Recent Drafts:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name5; ?>" value="<?php echo $opt_val5; ?>" size="20">
</p>
<p><?php _e("Recent Comments:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name6; ?>" value="<?php echo $opt_val6; ?>" size="20">
</p>
<p><?php _e("WordPress Blog:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name7; ?>" value="<?php echo $opt_val7; ?>" size="20">
</p>
<p><?php _e("WordPress News:", 'disable-dashboards' ); ?> 
<input type="text" name="<?php echo $data_field_name8; ?>" value="<?php echo $opt_val8; ?>" size="20">
</p>

<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
 
}

/* Run Function */
function remove_dashboard_widgets() {
    global $wp_meta_boxes;

    // variables for the field and option names 
    $quickpress = 'quickpress';
    $incominglinks = 'incominglinks';
    $rightnow = 'rightnow';
    $plugins = 'plugins';
    $recentdrafts = 'recentsdrafts';
    $recentcomments = 'recentcomments';
    $primarywp = 'primarywp';
    $secondarywp = 'secondarywp';

    // Read in existing option value from database
    $opt_val1 = get_option( $quickpress );
    $opt_val2 = get_option( $incominglinks );
    $opt_val3 = get_option( $rightnow );
    $opt_val4 = get_option( $plugins );
    $opt_val5 = get_option( $recentdrafts );
    $opt_val6 = get_option( $recentcomments );
    $opt_val7 = get_option( $primarywp );
    $opt_val8 = get_option( $secondarywp );

	if ($opt_val1 != '1') {
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	}
	if ($opt_val2 != '1') {
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	}
	if ($opt_val3 != '1') {
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	}
	if ($opt_val4 != '1') {
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	}
	if ($opt_val5 != '1') {
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	}
    	if ($opt_val6 != '1') {
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	}
	if ($opt_val7 != '1') {
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	}
	if ($opt_val8 != '1') {
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	}
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

?>