<?php
/**
 * Plugin Name: Block Styles
 */

namespace Block_Styles;

const BLOCK_STYLES_PLUGIN_DIR = __DIR__;

require_once __DIR__ . '/classes/class-base.php';
require_once __DIR__ . '/classes/class-style.php';
require_once __DIR__ . '/classes/class-admin-page.php';
require_once __DIR__ . '/classes/class-styles-collection.php';
require_once __DIR__ . '/classes/class-theme-json.php';
require_once __DIR__ . '/classes/class-preview.php';

Base::get_instance();