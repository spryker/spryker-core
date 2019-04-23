const contentItemDialog = function() {
    const insertContentText = window.contentItemConfiguration.cms.title;

    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.loaderTemplate = '<div>' +
                '<img src="/assets/img/cms-loader.gif" />' +
                '</div>';

            this.initialize = function() {
                const $container = this.options.dialogsInBody ? this.$body : this.editor;
                const bodyTemplate = '<div class="content-item-body">' +
                    this.loaderTemplate +
                    '</div>';

                const footerTemplate = '<div class="content-item-footer">' +
                    '<button class="btn btn-create safe-submit add-content-item">' +
                    insertContentText +
                    '</button>' +
                    '</div>';

                this.$dialog = this.$ui.dialog({
                    title: insertContentText,
                    fade: this.options.dialogsFade,
                    body: bodyTemplate,
                    footer: footerTemplate
                }).render().appendTo($container);

                this.mapEvents();
            };

            this.mapEvents = function () {
                this.$dialog.find('.add-content-item').on('click', function (event) {
                    event.preventDefault();
                    alert('Add Item');
                });
            }

            this.show = function (event, button) {
                this.context.invoke('editor.saveRange');

                this.$ui.showDialog(this.$dialog);
            }
        }
    });
};

module.exports = {
    init: contentItemDialog
};
