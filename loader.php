<?php

/*
Plugin Name: BuddyForms BuddyPress Sensei
Plugin URI: http://buddyforms.com/downloads/buddyforms-advanced-custom-fields/
Description: Integrates the populare ACF Plugin with BuddyForms. Use all ACF Fields in your form like native BuddyForms Form Elements
Version: 0.1
Author: Sven Lehnert
Author URI: https://profiles.wordpress.org/svenl77
License: GPLv2 or later
Network: false

*****************************************************************************
*
* This script is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
****************************************************************************
*/


add_filter('buddyforms_front_js_css_loader', 'buddyforms_bp_sensei_front_js_css_loader');

function buddyforms_bp_sensei_front_js_css_loader(){
  return true;
}

add_action('bp_sensei_create_courses_page','buddyforms_bp_sensei_create_courses_page');

function buddyforms_bp_sensei_create_courses_page(){
  global $buddyforms, $buddyforms_bp_sensei;
  $buddyforms_bp_sensei 	= get_option( 'buddyforms_bp_sensei' );

  if(isset($buddyforms_bp_sensei['courses']) && $buddyforms_bp_sensei['courses'] != 'none'){
    add_action( 'bp_template_title', 'bf_bp_sensei_completed_courses_page_title' );
    add_action( 'bp_template_content', 'bf_bp_sensei_completed_courses_page_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
  }

}
function bf_bp_sensei_completed_courses_page_title(){
  global $buddyforms, $buddyforms_bp_sensei;
  echo $buddyforms[$buddyforms_bp_sensei['courses']]['name'];
}
function bf_bp_sensei_completed_courses_page_content(){
  global $buddyforms, $buddyforms_bp_sensei;
  $args = array(
    'form_slug'		=> $buddyforms_bp_sensei['courses']
  );
  buddyforms_create_edit_form($args);
}

function buddyforms_bp_sensei_register_option() {
    // creates our settings in the options table
    register_setting('buddyforms_bp_sensei', 'buddyforms_bp_sensei', 'buddyforms_bp_sensei_sanitize' );
}
add_action('admin_init', 'buddyforms_bp_sensei_register_option');

function buddyforms_bp_sensei_sanitize($new){
    return $new;
}

add_filter('bf_admin_tabs', 'bf_bp_sensei');

function bf_bp_sensei($tabs){
$tabs['sensei'] = 'Sensei';
return $tabs;

}

add_action('buddyforms_settings_page_tab', 'bf_bp_sensei_settings_page_tab', 10, 1);
function bf_bp_sensei_settings_page_tab($tab){
  global $buddyforms;

  if($tab != 'sensei')
    return;

  $buddyforms_bp_sensei 	= get_option( 'buddyforms_bp_sensei' ); ?>
  <h2><?php _e('Sensei Frontent Forms', 'buddyforms'); ?></h2>
  <p>Select the Forms you want to use for your Courses</p>

  <form method="post" action="options.php">
    <?php settings_fields('buddyforms_bp_sensei'); ?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row" valign="top">
              Courses Form
          </th>
          <td>
            <select name="buddyforms_bp_sensei[courses]" class="regular-radio">
                <option value="none">None</option>
                <?php foreach ($buddyforms as $form_slug => $form) { ?>
                    <option <?php echo selected($buddyforms_bp_sensei['courses'], $form_slug, true) ?>
                        value="<?php echo $form_slug ?>"><?php echo $form['name'] ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <!-- <tr valign="top">
          <th scope="row" valign="top">
              Lessons Form
          </th>
          <td>
            <select name="buddyforms_bp_sensei[lessons]" class="regular-radio">
                <option value="none">None</option>
                <?php foreach ($buddyforms as $form_slug => $form) { ?>
                    <option <?php echo selected($buddyforms_bp_sensei['lessons'], $form_slug, true) ?>
                        value="<?php echo $form_slug ?>"><?php echo $form['name'] ?></option>
                <?php } ?>
            </select>
          </td>
        </tr> -->
      </tbody>
    </table>
    <?php submit_button(); ?>
  </form>
<?php
}
