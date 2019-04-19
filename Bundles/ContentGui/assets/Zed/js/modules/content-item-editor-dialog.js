const contentItemDialog = function() {
    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.ui = $.summernote.ui;

            this.initialize = function() {
                const $container = this.options.dialogsInBody ? this.body : this.editor;
                const body = '<div class="content-item-body">Test body</div>';
                const footer = '<div class="content-item-footer">' +
                               '<button class="btn btn-create safe-submit add-content-item">Add</button>' +
                               '</div>';

                this.$dialog = this.ui.dialog({
                    title: 'Add Content',
                    fade: this.options.dialogsFade,
                    body: body,
                    footer: footer
                }).render().appendTo($container);

                this.mapEvents();
            };

            this.mapEvents = function () {
                this.$dialog.find('.add-content-item').on('click', function (event) {
                    event.preventDefault();
                    alert('Add Item');
                });
            }

            this.show = function (event, btn) {
                event.preventDefault();
                this.context.invoke('editor.saveRange');

                this.ui.showDialog(this.$dialog);
            }
        }
    });
};

module.exports = {
    init: contentItemDialog
};
