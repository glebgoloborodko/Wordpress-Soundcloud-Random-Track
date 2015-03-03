<?php 
/**
 * Plugin Name: Soundcloud Random Track
 * Plugin URI: http://mesmerized.info
 * Description: A Plugin that enables [soundcloud_random] tag to be used for displaying a random widget from a pre-defined list
 * Version: 0.1.0
 * Author: Gleb Goloborodko
 * Author URI: http://www.goloborodko.com
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */


register_activation_hook(__FILE__, 'wstr_install');

function wstr_install()
{
	// installs plugin
	// create array in options
	add_option('wstr_options_array',array());
	add_option('wstr_params',array());
}


register_deactivation_hook(__FILE__, 'wstr_deactivate');

function wstr_deactivate()
{
	// deactivates plugin
	// deletes array in options
	delete_option('wstr_options_array');
	delete_option('wstr_params');
}

function load_style_js()
{
	wp_register_style( 'wstr', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('wstr');

	wp_register_script( 'wstr', plugins_url('main.js',__FILE__ ));
	wp_enqueue_script('wstr');
}

add_filter("the_content", 'wstr_filter');

function wstr_filter($content)
{
	$replace = '[soundcloud_random]';
	$wstr_options = get_option('wstr_options_array');
	$wstr_params = get_option('wstr_params');
	$object = $wstr_options[rand(0,count($wstr_options)-1)];
	$object = stripcslashes($object);
	$content = str_replace($replace, $object, $content);

	// regular expression for width & height
	$pattern_width = '/width=\".*?\"/';
	if($wstr_params['width'])
		$content = preg_replace($pattern_width,'width="'.$wstr_params['width'].'"',$content);

	$pattern_height = '/height=\".*?\"/';
	if($wstr_params['height'])
		$content = preg_replace($pattern_height,'height="'.$wstr_params['height'].'"',$content);

	return $content;
}

// admdin part ------------------------

function save_data()
{
	// saving data to datbase using wp_option
}

function load_data()
{
	// loading data from datbase using wp_option
}

add_action('admin_menu', 'wstr_create_menu');

function wstr_create_menu()
{
	add_menu_page('SoundCloud Plugin Page',
				  'SC Plugin',
				  'manage_options',
				  'wstr_main_menu_page',
				  'wstr_settings_page');
	add_action('admin_init', 'wstr_register_settings');
}

function wstr_register_settings()
{
	register_setting('wstr-settings-group','wstr_options','wstr_sanitize_options');
}

function wstr_settings_page()
{
	load_style_js();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		// removing empty values
		$_POST['wstr_options'] = array_filter($_POST['wstr_options']);

	    if(isset($_POST['wstr_options']))
	    	update_option('wstr_options_array',$_POST['wstr_options']);

	    $params = array();
	    if(isset($_POST['wstr_width']))
	    	$params['width'] = $_POST['wstr_width'];
	    if(isset($_POST['wstr_height']))
	    	$params['height'] = $_POST['wstr_height'];

	    update_option('wstr_params',$params);
	}

	$params = get_option('wstr_params');

	settings_fields('wstr-settings-group');
	$wstr_options = get_option('wstr_options_array');
	foreach($wstr_options as &$option_temp)
		$option_temp = stripcslashes($option_temp);

	?>		
	<div id="wstr_wrap" class=wrap>
		<h2>SoundCloud Random Track settings page</h2>

		<form method="post" actions='options.php' id="wstr_form">

			<p>
				<button type="button" id="wstr_addnew" onclick="wstrAddNewTrack(this);">Add new</button>
			</p>

			<?php $index = 0; ?>
			<?php foreach($wstr_options as $option): ?>
	
				<div id="wstr_track">
					<label>Widget code: 
					<textarea class="wstr_value" name="wstr_options[]"><?php echo esc_attr($option)?></textarea>
					</label>
					<button class="wstr_remove" type="button" onclick="wstrRemoveTrack(this);">X</button>
					<!-- <br /> -->
				</div>
			<?php endforeach;?>

			<label>
				Width:
				<input name="wstr_width" class="wstr_options" value="<?php echo $params['width']?>"/>
			</label>

			<label>
				Height:
				<input name="wstr_height" class="wstr_options" value="<?php echo $params['height']?>"/>
			</label>

			<p class=submit>
				<input type="submit" class="button-primary" value="Save changes" />
			</p>
		</form>
	</div>
	<?
}

function wstr_sanitize_options($input)
{
	// echo "sanitizing"; die;
	// $input['option1'] = esc_url($input['option1']);
	// $input['option2'] = esc_url($input['option2']);
	// $input['option3'] = esc_url($input['option3']);
	// $input['option4'] = esc_url($input['option4']);
	return $input;
}

// enf of admin part ------------------

?>