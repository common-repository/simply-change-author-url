<?php
if ( ! function_exists( 'set_add_simply_change_author_url_rules' ) ) {
	/**
	 * sets the value of flush_rewrite_rules
	 *
	 * @param bool $set
	 */
	function set_add_simply_change_author_url_rules( $set = false ) {
		set_transient( 'add_simply_change_author_url_rules', $set, 600 );
	}
}

if ( ! function_exists( 'get_add_simply_change_author_url_rules' ) ) {
	/**
	 * gets the value of flush_rewrite_rules
	 *
	 * @return  bool
	 */
	function get_add_simply_change_author_url_rules() {
		return get_transient( 'add_simply_change_author_url_rules' );
	}
}

if ( ! function_exists( 'delete_add_simply_change_author_url_rules' ) ) {
	/**
	 * delete the value of flush_rewrite_rules and returns it
	 *
	 * @return bool
	 */
	function delete_add_simply_change_author_url_rules() {
		$add = get_transient( 'add_simply_change_author_url_rules' );
		delete_transient( 'add_simply_change_author_url_rules' );

		return $add;
	}
}
