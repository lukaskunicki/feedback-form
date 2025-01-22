<?php
/**
 * Plugin Name: Feedback Block
 * Description: A Gutenberg block for feedback form submissions
 * Version: 1.0.0
 * Author: Lukasz Kunicki
 * Text Domain: feedback-block
 * Requires at least: 6.1
 * Requires PHP: 7.4
 *
 * @package FeedbackBlock
 */

declare(strict_types=1);

namespace FeedbackBlock;

use FeedbackBlock\Includes\FeedbackSubmissions;

if (!defined('ABSPATH')) {
	exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Main plugin class
 *
 * @package FeedbackBlock
 */
class FeedbackBlock {
	/**
	 * Plugin instance.
	 *
	 * @var FeedbackBlock|null
	 */
	private static ?FeedbackBlock $instance = null;

	/**
	 * Table name for submissions.
	 *
	 * @var string
	 */
	private string $table_name;

	/**
	 * Submissions handler instance.
	 *
	 * @var FeedbackSubmissions
	 */
	private FeedbackSubmissions $submissions;

	/**
	 * Get plugin instance.
	 *
	 * @return FeedbackBlock Plugin instance.
	 */
	public static function getInstance(): FeedbackBlock {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'feedback_submissions';
		$this->submissions = new FeedbackSubmissions();

		add_action('init', [$this, 'registerBlocks']);
		add_action('plugins_loaded', [$this, 'loadTextDomain']);
	}

	/**
	 * Prevent cloning of the instance.
	 *
	 * @return void
	 */
	private function __clone(): void {}

	/**
	 * Prevent unserializing of the instance.
	 *
	 * @return void
	 */
	public function __wakeup(): void {}

	/**
	 * Register blocks.
	 *
	 * @return void
	 */
	public function registerBlocks(): void {
		register_block_type(__DIR__ . '/build/submission-form');
		register_block_type(__DIR__ . '/build/submission-list');
	}

	/**
	 * Load plugin text domain.
	 *
	 * @return void
	 */
	public function loadTextDomain(): void {
		load_plugin_textdomain(
			'feedback-block',
			false,
			dirname(plugin_basename(__FILE__)) . '/languages'
		);
	}

	/**
	 * Activate plugin.
	 *
	 * @return void
	 */
	public static function activate(): void {
		global $wpdb;

		$table_name = $wpdb->prefix . 'feedback_submissions';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			first_name varchar(100) NOT NULL,
			last_name varchar(100) NOT NULL,
			email varchar(100) NOT NULL,
			subject varchar(200) NOT NULL,
			message text NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}
}

add_action('plugins_loaded', [FeedbackBlock::class, 'getInstance']);

register_activation_hook(__FILE__, [FeedbackBlock::class, 'activate']);
