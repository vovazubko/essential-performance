<?php
/**************************************************************************
 * Essential Performance
 *
 * @package     Essential Performance
 * @author      Vova Zubko <vozubko@gmail.com>
 * @copyright   2019 Vova Zubko <vozubko@gmail.com>
 * @license     GPL-2.0-or-later
 * @site        https://vauko.com
 * @file        plugin.php
 * @date        10/19/2019
 *
 * @wordpress-plugin
 * Plugin Name:       Essential Performance
 * Description:       Essential Performance plugin for Wordpress.
 * Version:           0.0.3
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Vova Zubko <vozubko@gmail.com>
 * Author URI:        https://vauko.com
 * Text Domain:       essential-performance
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined('ABSPATH') || exit;

/**
 * Loader
 */
require_once __DIR__ . '/src/framework/loader.php';

get_EssentialPerformance();