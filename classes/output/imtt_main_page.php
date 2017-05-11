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
    public function __construct($DB, $params) {
        $this->course = $params['course'];
        $this->error = $params['error'];

        $this->google_auth =
            new \local_imtt\auth\google_auth($this->course->id);

        $this->imtt_instance =
            \local_imtt\model\imtt_instance::find_by_course_id($DB, $this->course->id);

        $this->pipelines = self::load_pipelines();
    }


    public static function load_pipelines() {
        $pipelines_dir = dirname(dirname(__DIR__)).'/pipelines';
        $all_files = scandir($pipelines_dir);
        $pipelines = array();

        foreach ($all_files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext == 'json') {
                $pipeline_json = file_get_contents($pipelines_dir.'/'.$file);
                $pipeline = json_decode($pipeline_json, true);
                array_push($pipelines, $pipeline);
            }
        }

        return $pipelines;
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
        $data->pipelines = $this->pipelines;

        if ($this->imtt_instance != false) {
            $data->imtt_id = $this->imtt_instance->id;
            $data->imtt_configuration = $this->imtt_instance->configuration_json;
        } else {
            $data->imtt_id = -1;
        }

        return $data;
    }
}
?>