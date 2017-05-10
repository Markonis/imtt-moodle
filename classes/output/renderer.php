<?php

namespace local_imtt\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use renderable;

/**
 * Renderer class for If Moodle Than That
 *
 * @package    local_imtt
 * @copyright  2017 Marko Pavlovic <marko.pavlovic@elfak.rs>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {
     /**
     * Defer to template.
     *
     * @return string html for the page
     */
    public function render_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_imtt/imtt_main_page', $data);
    }
}

?>