<?php

if ( !class_exists( 'BP_Group_Reviews_Upgrade' ) ) :

class BP_Group_Reviews_Upgrade {
	function bp_group_reviews_upgrade() {
		$this->__construct();
	}
	
	function __construct() {
		$this->current_version = get_option( 'bp_group_reviews_version' );
		$this->new_version = BP_GROUP_REVIEWS_VERSION;
		
		// No version number existed before version 1.02
		if ( empty( $this->current_version ) ) {
			$this->upgrade_1_02();
		}
	
		//update_option( 'bp_group_reviews_version', $this->new_version );
	}
	
	function upgrade_1_02() {
		global $bp, $wpdb;
		print_r($bp);
		$sql = $wpdb->prepare( "SELECT group_id, meta_value FROM {$bp->groups->table_name_groupmeta} WHERE meta_key = 'bpgr_rating'" );
		
		$old_ratings = $wpdb->get_results( $sql );
		
		print_r($old_ratings);
	}
}

endif;

$bpgr_upgrade = new BP_Group_Reviews_Upgrade;

?>