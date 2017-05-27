define(['jquery', 'local_imtt/vue', 'core/ajax'], function($, Vue, ajax) {
    var selector = '#imtt-configuration-editor';
    var $el = $(selector);

    function readJSONAttr(element, attr, defaultValue) {
        try {
            return JSON.parse(element.attr(attr));
        }
        catch (ex) {
            return defaultValue;
        }
    }

    function copy(object) {
        return $.extend(true, {}, object);
    }

    var editorPipelines = [{
        "spec_version": 1,
        "pipeline_version": 1,
        "name": "Sinhronizacija assignment-a sa Google Sheets",
        "description": "Kada student uradi submit za odredjeni assignment, u izabrani Google Sheets fajl se u zadatoj koloni za tog studenta upise zadata vrednost.",
        "author": "Marko Pavlovic",
        "license": "GPL-3.0",
        "trigger": {
            "type": "moodle_event",
            "event_name": "\\mod_assign\\event\\assessable_submitted"
        },
        "params": {
            "moodle_assignment_id": {
                "type": "select",
                "options": "assignments",
                "label": "Izaberite zadatak"
            },
            "google_sheet_id": {
                "type": "select",
                "options": "googleSheets",
                "label": "Izaberite Google Sheets dokument"
            },
            "google_sheet_page": {
                "type": "text",
                "label": "Upisite ime strane u Google Sheets dokumentu"
            },
            "google_sheet_key_column": {
                "type": "text",
                "label": "Unesite kolonu koja sadrzi indeks studenta"
            },
            "google_sheet_value_column": {
                "type": "text",
                "label": "Unesite kolonu u koju ce se upisati vrednost"
            },
            "google_sheet_value": {
                "type": "text",
                "label": "Unesite vrednost koju treba upisati"
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
    }];

    return {
        init: function() {
            var app = new Vue({
                el: selector,
                data: {
                    imttInstance: {
                        id: readJSONAttr($el, 'data-imtt-id', null),
                        configuration: readJSONAttr($el, 'data-imtt-configuration', {
                            pipelines: []
                        })
                    },
                    editor: {
                        googleSheets: readJSONAttr($el, 'data-google-sheets', []),
                        assignments: readJSONAttr($el, 'data-assignments', []),
                        pipelines: editorPipelines
                    },
                    chosenPipeline: editorPipelines[0]
                },
                methods: {
                    addPipeline: function() {
                        this.imttInstance.configuration
                            .pipelines.push(copy(this.chosenPipeline));
                    },
                    removePipeline: function(index) {
                        this.imttInstance.configuration
                            .pipelines.splice(index, 1);
                    },
                    save: function() {
                        var self = this;
                        var request = {
                            methodname: 'local_imtt_save_configuration',
                            args: {
                                imtt_instance: {
                                    id: self.imttInstance.id,
                                    configuration_json: JSON.stringify(self.imttInstance.configuration)
                                }
                            }
                        };

                        ajax.call([request])[0].done(function(response) {
                            console.log('Saved!');
                        });
                    },
                    selectOptions: function(param) {
                        return this.editor[param.options];
                    }
                }
            });
        }
    };
});
