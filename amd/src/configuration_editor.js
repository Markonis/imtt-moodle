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
                        configuration: {
                            pipelines: readJSONAttr($el, 'data-imtt-configuration', [])
                        }
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

                    }
                }
            });
        }
    };
});
