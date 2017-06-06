<?php
namespace local_imtt\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

use local_imtt\http;

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
        $this->course_id = $params['course_id'];
        $this->error = $params['error'];
        $this->require_js_strings();

        $this->google_auth =
            new \local_imtt\auth\google_auth($this->course_id);

        $this->imtt_instance =
            \local_imtt\model\imtt_instance::find_by_course_id($this->DB, $this->course_id);

        $this->assignments = $this->load_course_assignments();
        $this->quizes = $this->load_course_quizes();

        if ($this->imtt_instance != false) {
            $this->imtt_instance->refresh_provider_token();
            $this->google_sheets = $this->load_google_sheets();
            $this->PAGE->requires->js_call_amd('local_imtt/configuration_editor', 'init');
        }
    }

    public function load_google_sheets() {
        $path = '/api/google/sheets/list';
        $middleman_url = $this->get_config('middlemanurl');
        $middleman_port = $this->get_config('middlemanport');
        $token = $this->imtt_instance->provider_access_token;

        $url = $middleman_url . ':' . $middleman_port . $path;
        $request_data = array('token' => $token);

        $result = http\helper::send_json_request($url, $request_data);
        if ($result['status'] == 200) {
            return $this->google_sheets_options($result['data']);
        } else {
            return null;
        }
    }

    public function google_sheets_options($sheets) {
        $result = array();
        foreach ($sheets as $sheet) {
            array_push($result, array(
                'value' => $sheet['id'],
                'label' => $sheet['name']));
        }
        return $result;
    }

    public function load_course_assignments() {
        $records = $this->DB->get_records('assign',
            array('course' => $this->course_id));
        return $this->course_assignments_options($records);
    }

    public function course_assignments_options($assignments) {
        $result = array();
        foreach ($assignments as $assignment) {
            array_push($result, array(
                'value' => $assignment->id,
                'label' => $assignment->name));
        }
        return $result;
    }

    public function load_course_quizes() {
        $records = $this->DB->get_records('quiz',
            array('course' => $this->course_id));
        return $this->course_quizes_options($records);
    }

    public function course_quizes_options($quizes) {
        $result = array();
        foreach ($quizes as $quiz) {
            array_push($result, array(
                'value' => $quiz->id,
                'label' => $quiz->name));
        }
        return $result;
    }

    public function get_config($key) {
        return get_config('local_imtt', $key);
    }

    public function require_js_strings() {
        $stringman = get_string_manager();
        $strings = $stringman->load_component_strings('local_imtt', current_language());
        $this->PAGE->requires->strings_for_js(array_keys($strings), 'local_imtt');
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Renderer base.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new \stdClass();

        $data->course_id = $this->course_id;
        $data->error = $this->error;
        $data->google_sheets = json_encode($this->google_sheets);
        $data->assignments = json_encode($this->assignments);
        $data->quizes = json_encode($this->quizes);
        $data->auth_url = $this->google_auth->auth_url->out(false);
        if ($this->imtt_instance != false) {
            $data->imtt = $this->imtt_instance;
        } else {
            $data->imtt = null;
        }

        // I18N
        $data->str = get_strings(
            array('save','connect_google', 'add_pipeline', 'add', 'saved'),
            'local_imtt');

        return $data;
    }
}
?>