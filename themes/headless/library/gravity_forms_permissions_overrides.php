<?php

/**
 * Add all Gravity Forms capabilities to Editor role.
 * Runs when this theme is activated.
 * 
 * @access public
 * @return void
 */
function grant_gforms_editor_access() {
  
  $role = get_role( 'editor' );
  $role->add_cap( 'gform_full_access' );
}
// Tie into the 'after_switch_theme' hook
add_action( 'after_switch_theme', 'grant_gforms_editor_access' );

/**
 * Remove Gravity Forms capabilities from Editor role.
 * Runs when this theme is deactivated (in favor of another).
 * 
 * @access public
 * @return void
 */
function revoke_gforms_editor_access() {
  
  $role = get_role( 'editor' );
  $role->remove_cap( 'gform_full_access' );
}
// Tie into the 'switch_theme' hook
add_action( 'switch_theme', 'revoke_gforms_editor_access' );

?>