<?php
namespace local_imtt\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Class containing data for IMTT main page
 *
 * @package    local_imtt
 * @copyright  2017 Marko Pavlovic <marko.pavlovic@elfak.rs>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class imtt_main_page implements renderable, templatable {
    /**
     * Construct the page renderable and build the Google OAuth client.
     */
    public function __construct($params) {
        $this->DB = $params['DB'];
        $this->PAGE = $params['PAGE'];
        $this->course = $params['course'];
        $this->error = $params['error'];

        $this->google_auth =
            new \local_imtt\auth\google_auth($this->course->id);

        $this->imtt_instance =
            \local_imtt\model\imtt_instance::find_by_course_id($this->DB, $this->course->id);

        if ($this->imtt_instance != false) {
            $this->PAGE->requires->js_call_amd('local_imtt/configuration_editor', 'init');
        }
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Renderer base.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new \stdClass();

        $data->course_id = $this->course->id;
        $data->error = $this->error;
        $data->auth_url = $this->google_auth->auth_url->out(false);

        if ($this->imtt_instance != false) {
            $data->imtt = $this->imtt_instance;
        } else {
            $data->imtt = null;
        }

        return $data;
    }
}
?>