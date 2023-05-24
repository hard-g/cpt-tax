<?php
/**
 * Registry for linked Custom Post Types and taxonomies
 */

namespace HardG\CPTTax;

use \Exception;

/**
 * Registry.
 */
class Registry {
	/**
	 * Registered links.
	 *
	 * @var array
	 */
	protected $links = [];

	/**
	 * Gets the instance of the registry.
	 *
	 * @return HardG\CPTTax\Registry
	 */
	protected static function get_instance() {
		static $instance;

		if ( empty( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Registers a linked CPT and taxonomy.
	 *
	 * @return boolean True on success. False if it already is registered.
	 */
	public static function register( $post_type, $taxonomy ) {
		if ( ! function_exists( '\post_type_exists' ) ) {
			throw new Exception( 'WordPress must be installed to use this library.' );
		}

		if ( ! post_type_exists( $post_type ) ) {
			throw new Exception( sprintf( 'Post type does not exist: %s', $post_type ) );
		}

		if ( ! taxonomy_exists( $taxonomy ) ) {
			throw new Exception( sprintf( 'Taxonomy does not exist: %s', $taxonomy ) );
		}

		$registry = self::get_instance();

		$key = $post_type . '-' . $taxonomy;

		if ( ! empty( $registry->links[ $key ] ) ) {
			return false;
		}

		$registry->links[ $key ] = new CPTTax( $post_type, $taxonomy );

		return true;
	}

}
