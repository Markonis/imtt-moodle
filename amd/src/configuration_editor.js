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

    var editorPipelines = readJSONAttr($el, 'data-pipelines', []);

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
                            console.log(response);
                        });
                    }
                }
            });
        }
    };
});
