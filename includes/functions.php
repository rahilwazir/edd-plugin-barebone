<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get Errors
 *
 * Retrieves all error messages stored during the checkout process.
 * If errors exist, they are returned.
 *
 * @since 1.0
 * @uses EDD_Session::get()
 * @return mixed array if errors are present, false if none found
 */
function eddvl_get_errors() {
	return EDD()->session->get( 'eddvl_errors' );
}

/**
 * Set Error
 *
 * Stores an error in a session var.
 *
 * @since 1.0
 * @uses EDD_Session::get()
 * @param int $error_id ID of the error being set
 * @param string $error_message Message to store with the error
 * @return void
 */
function edvl_set_error( $error_id, $error_message ) {
	$errors = eddvl_get_errors();
	if ( ! $errors ) {
		$errors = array();
	}
	$errors[ $error_id ] = $error_message;
	EDD()->session->set( 'eddvl_errors', $errors );
}

/**
 * Clears all stored errors.
 *
 * @since 1.0
 * @uses EDD_Session::set()
 * @return void
 */
function eddvl_clear_errors() {
	EDD()->session->set( 'eddvl_errors', null );
}

