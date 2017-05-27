define(['jquery', 'local_imtt/vue', 'core/ajax', 'local_imtt/pipelines'], function($, Vue, ajax, pipelines) {
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

    var editorPipelines = pipelines.getTranslated();

    return {
        init: function() {
            var app = new Vue({
                el: selector,
                data: {
                    dirty: true,
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
                            self.dirty = false;
                        });
                    },
                    selectOptions: function(param) {
                        return this.editor[param.options];
                    },
                    setDirty: function() {
                        this.dirty = true;
                    }
                }
            });
        }
    };
});
