<?php

function control_wp_plugin_remotely_task_events_activate() {
    if (! wp_next_scheduled ( 'cwppr_daily_schedules' )) {
        wp_schedule_event( time(), 'daily', 'cwppr_daily_schedules');
    }
}

add_action( 'cwppr_daily_schedules', 'cwppr_active_cron_job_after_24_hour', 10, 2 );
function cwppr_active_cron_job_after_24_hour() {
	$home_url = get_home_url();
	$notices = file_get_contents('http://your_site.com/cwppr_notices.php?version=pro&url='.$home_url);

    update_option('cwppr_notices', $notices);
}

function control_wp_plugin_remotely_task_events_deactivate() {
    wp_clear_scheduled_hook( 'cwppr_daily_schedules' );
}

function cwppr_right_side_notice(){
	$notices = get_option('cwppr_notices');
	$notices = json_decode($notices);
	$html = "";

	if (!empty($notices)) {
		foreach ($notices as $key => $notice) {
			$title = $notice->title;
			$key = $notice->key;
			$publishing_date = $notice->publishing_date;
			$auto_hide = $notice->auto_hide;
			$auto_hide_date = $notice->auto_hide_date;
			$is_right_sidebar = $notice->is_right_sidebar;
			$content = $notice->content;
			$status = $notice->status;
			$version = isset($notice->version) ? $notice->version : array();
			$styles = isset($notice->styles) ? $notice->styles : "";

			$current_time = time();
			$publish_time = strtotime($publishing_date);
			$auto_hide_time = strtotime($auto_hide_date);

			if ( $status && $is_right_sidebar == 1 && $current_time > $publish_time && $current_time < $auto_hide_time && in_array('pro', $version) ) {
				$html .= '<div class="sidebar_notice_section">';
				$html .=	'<div class="right_notice_title">'.$title.'</div>';
				$html .=	'<div class="right_notice_details">'.$content.'</div>';
				$html .= '</div>';

				if ( !empty($styles) ) {
					$html .= '<style>' . $styles . '</style>';
				}
			}
		}
	}


	echo '<div class="right_side_notice">' . $html . '</div>';
}

/*Main action filter. You have to add this to your plugin's settings page.*/
add_action("cwppr_right_side_notice", "cwppr_right_side_notice"); 


function cwppr_admin_notices(){


	$notices = get_option('cwppr_notices');
	$notices = json_decode($notices);
	$html = "";

	
	if (!empty($notices)) {
		foreach ($notices as $key2 => $notice) {
			$title = isset($notice->title) ? $notice->title : "";
			$key = isset($notice->key) ? $notice->key : "";
			$publishing_date = isset($notice->publishing_date) ? $notice->publishing_date : time();
			$auto_hide = isset($notice->auto_hide) ? $notice->auto_hide : false;
			$auto_hide_date = isset($notice->auto_hide_date) ? $notice->auto_hide_date : time();
			$is_right_sidebar = isset($notice->is_right_sidebar) ? $notice->is_right_sidebar : true;
			$content = isset($notice->content) ? $notice->content : "";
			$status = isset($notice->status) ? $notice->status : false;
			$alert_type = isset($notice->alert_type) ? $notice->alert_type : "success";
			$version = isset($notice->version) ? $notice->version : array();
			$styles = isset($notice->styles) ? $notice->styles : "";

			$current_time = time();
			$publish_time = strtotime($publishing_date);
			$auto_hide_time = strtotime($auto_hide_date);

			$clicked_data = (array) get_option('cwppr_notices_clicked_data');

			if ( $status && !$is_right_sidebar && $current_time > $publish_time && $current_time < $auto_hide_time && !in_array($key, $clicked_data) && in_array('pro', $version) ) {
				$html .=  '<div class="notice notice-'. $alert_type .' is-dismissible dcim-alert cwppr" cwppr_notice_key="'.$key.'">
						'.$content.'
					</div>';

				if ( !empty($styles) ) {
					$html .= '<style>' . $styles . '</style>';
				}
			}
		}
	}

	echo $html;

}
add_action('admin_notices', 'cwppr_admin_notices');


			
add_action('wp_ajax_cwppr_notice_has_clicked', 'cwppr_notice_has_clicked');
add_action('wp_ajax_nopriv_cwppr_notice_has_clicked', 'cwppr_notice_has_clicked');

function cwppr_notice_has_clicked(){
	//$post = $_POST['post'];
	$cwppr_notice_key = isset($_POST['cwppr_notice_key']) ? $_POST['cwppr_notice_key'] : "";
	$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";

	if(!empty($nonce)){
		if(!wp_verify_nonce( $nonce, "recorp_different_menu" )){
			echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

			die();
		}
	}

	set_cwppr_notices_clicked_data($cwppr_notice_key);

	$response = "";

	
	echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));

	die();
}


	function set_cwppr_notices_clicked_data($new = ""){

		$gop = get_option('cwppr_notices_clicked_data');

		if (!empty($gop)) {

			if (!empty($new)) {
				$gop[] = $new;
			}
			

		} else {
			$gop = array();
			$gop[] = $new;
		}

		update_option('cwppr_notices_clicked_data', $gop);

		return $gop;
	}

function rc_cwppr_notice_dissmiss_scripts(){
	?>
	<script>
		jQuery(document).on("click", ".cwppr .notice-dismiss", function(){
			if (jQuery(this).parent().attr('cwppr_notice_key').length) {
				 var datas = {
				  'action': 'cwppr_notice_has_clicked',
				  'rc_nonce': '<?php echo wp_create_nonce( "recorp_different_menu" ); ?>',
				  'cwppr_notice_key': jQuery(this).parent().attr('cwppr_notice_key'),
				};
				
				jQuery.ajax({
				    url: '<?php echo admin_url('admin-ajax.php'); ?>',
				    data: datas,
				    type: 'post',
				    dataType: 'json',
				
				    beforeSend: function(){
				
				    },
				    success: function(r){
				      if(r.success == 'true'){
				        console.log(r.response);
				
				        
				        } else {
				          alert('Something went wrong, please try again!');
				        }
				    	
				    }, error: function(){
				    	
				  }
				});
			}
		});
	</script>
	<?php
}
add_action("admin_footer", "rc_cwppr_notice_dissmiss_scripts");
