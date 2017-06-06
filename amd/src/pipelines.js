define(['jquery'], function($) {
    var definitions = [{
        "spec_version": 1,
        "id": "assignment_submission_sheets",
        "pipeline_version": 1,
        "author": "Marko Pavlovic",
        "license": "GPL-3.0",
        "trigger": {
            "type": "moodle_event",
            "event_name": "\\mod_assign\\event\\assessable_submitted"
        },
        "params": {
            "moodle_assignment_id": {
                "type": "select",
                "options": "assignments"
            },
            "google_sheet_id": {
                "type": "select",
                "options": "googleSheets"
            },
            "google_sheet_page": {
                "type": "text"
            },
            "google_sheet_key_column": {
                "type": "text"
            },
            "google_sheet_value_column": {
                "type": "text"
            },
            "google_sheet_value": {
                "type": "text"
            }
        },
        "processors": [
            {
                "type": "filter",
                "condition": {
                    "type": "equal",
                    "op1": {
                        "type": "data",
                        "source": "params.moodle_assignment_id"
                    },
                    "op2": {
                        "type": "data",
                        "source": "trigger.object.assignment"
                    }
                }
            },
            {
                "type": "middleman_request",
                "path": "/api/google/sheets/update",
                "params": {
                    "token": {
                        "source": "imtt.provider_access_token",
                    },
                    "sheet_id": {
                        "source": "params.google_sheet_id"
                    },
                    "page": {
                        "source": "params.google_sheet_page"
                    },
                    "key_column": {
                        "source": "params.google_sheet_key_column"
                    },
                    "key_value": {
                        "source": "trigger.user.email"
                    },
                    "value_column": {
                        "source": "params.google_sheet_value_column"
                    },
                    "value": {
                        "source": "params.google_sheet_value"
                    }
                }
            }
        ]
    }, {
        "spec_version": 1,
        "id": "quiz_submission_sheets",
        "pipeline_version": 1,
        "author": "Marko Pavlovic",
        "license": "GPL-3.0",
        "trigger": {
            "type": "moodle_event",
            "event_name": "\\mod_quiz\\event\\attempt_submitted"
        },
        "params": {
            "moodle_quiz_id": {
                "type": "select",
                "options": "quizes"
            },
            "google_sheet_id": {
                "type": "select",
                "options": "googleSheets"
            },
            "google_sheet_page": {
                "type": "text"
            },
            "google_sheet_key_column": {
                "type": "text"
            },
            "google_sheet_grade_column": {
                "type": "text"
            },
            "google_sheet_timestamp_column": {
                "type": "text"
            }
        },
        "processors": [
            {
                "type": "filter",
                "condition": {
                    "type": "equal",
                    "op1": {
                        "type": "data",
                        "source": "params.moodle_quiz_id"
                    },
                    "op2": {
                        "type": "data",
                        "source": "trigger.object.quiz"
                    }
                }
            },
            {
                "type": "middleman_request",
                "path": "/api/google/sheets/update",
                "params": {
                    "token": {
                        "source": "imtt.provider_access_token",
                    },
                    "sheet_id": {
                        "source": "params.google_sheet_id"
                    },
                    "page": {
                        "source": "params.google_sheet_page"
                    },
                    "key_column": {
                        "source": "params.google_sheet_key_column"
                    },
                    "key_value": {
                        "source": "trigger.user.email"
                    },
                    "value_column": {
                        "source": "params.google_sheet_grade_column"
                    },
                    "value": {
                        "source": "trigger.object.sumgrades"
                    }
                }
            },
            {
                "type": "middleman_request",
                "path": "/api/google/sheets/update",
                "params": {
                    "token": {
                        "source": "imtt.provider_access_token",
                    },
                    "sheet_id": {
                        "source": "params.google_sheet_id"
                    },
                    "page": {
                        "source": "params.google_sheet_page"
                    },
                    "key_column": {
                        "source": "params.google_sheet_key_column"
                    },
                    "key_value": {
                        "source": "trigger.user.email"
                    },
                    "value_column": {
                        "source": "params.google_sheet_timestamp_column"
                    },
                    "value": {
                        "source": "trigger.object.timefinish"
                    }
                }
            }
        ]
    }];

    function copy(object) {
        return $.extend(true, {}, object);
    }

    function str(path) {
        var strings = M.str.local_imtt;
        return strings[path.join('.')];
    }

    function translate(def) {
        var result = copy(def);
        result.name = str(['pipelines', result.id, 'name']);
        for (var paramKey in result.params) {
            result.params[paramKey].label =
                str(['pipelines', result.id, 'params', paramKey]);
        }
        return result;
    }

    return {
        getTranslated: function() {
            var result = [];
            for (var i = 0; i < definitions.length; i++) {
                result.push(translate(definitions[i]));
            }
            return result;
        }
    };
});
