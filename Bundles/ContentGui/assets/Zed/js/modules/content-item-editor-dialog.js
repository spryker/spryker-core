const contentItemDialog = function() {
    const insertContentText = window.editorConfiguration.cms.title;

    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.isBodyTemplateAppended = false;

            this.initialize = function() {
                const $container = this.options.dialogsInBody ? this.$body : this.editor;
                const loaderTemplate = '<div class="content-item-loader text-center">' +
                    '<img src="/assets/img/cms-loader.gif" />' +
                    '</div>';
                const bodyTemplate = '<div class="content-item-body">' +
                    loaderTemplate +
                    '</div>';

                const footerTemplate = '<div class="content-item-footer">' +
                    '<button class="btn btn-create add-content-item">' +
                    insertContentText +
                    '</button>' +
                    '</div>';

                this.$dialog = this.$ui.dialog({
                    title: insertContentText,
                    fade: this.options.dialogsFade,
                    body: bodyTemplate,
                    footer: footerTemplate,
                }).render().appendTo($container);

                this.mapEvents();
            };

            this.clearError = function (container) {
                const $containerParent = container.parent();
                const $errors = $containerParent.find('.error');

                if ($errors.length > 0) {
                    $errors.remove();
                }
            }

            this.showError = function (errorSelector, container) {
                errorSelector.insertAfter(container);
            }

            this.mapEvents = function () {
                const self = this;
                this.$dialog.find('.add-content-item').on('click', function (event) {
                    event.preventDefault();
                    const $boxTitlteContainer = self.$dialog.find('.ibox-title h5');
                    const chooseId = self.$dialog.find('table input:checked').val();
                    const $choseIdErrorSelector = self.$dialog.find('.content-errors .item');
                    const chooseTemplate = self.$dialog.find('.template-list input:checked').val();
                    const $chooseTemplateErrorSelector = self.$dialog.find('.content-errors .template');
                    const $twigTemplate = self.$dialog.find('input[name=twigFunctionTemplate]');
                    const isHideDialog = chooseId !== undefined && chooseTemplate !== undefined;

                    self.clearError($boxTitlteContainer);

                    if (isHideDialog) {
                        $($twigTemplate).val("{{ content_banner(" + chooseId + ", '" + chooseTemplate + "'" + ") }}");
                        self.context.invoke('editor.insertText',  $($twigTemplate).val());
                        self.context.invoke('editor.restoreRange');
                        self.$ui.hideDialog(self.$dialog);
                        return;
                    }

                    if (!chooseId) {
                        self.showError($choseIdErrorSelector, $boxTitlteContainer)
                    }

                    if (!chooseTemplate) {
                        self.showError($chooseTemplateErrorSelector, $boxTitlteContainer);
                    }
                });
            }

            this.getTableContent = function (url) {
                if (!this.isBodyTemplateAppended) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: "html",
                        context: this,
                        success: function(data) {
                            const dataAjaxUrl = $(data).find('table').data('ajax');
                            this.$dialog.find('.content-item-loader').remove();
                            this.$dialog.find('.content-item-body').append(data);
                            this.$dialog.find('table').DataTable({'ajax': dataAjaxUrl});
                            this.isBodyTemplateAppended = true;
                        }
                    });
                }
            }

            this.show = function (params, buttons) {
                const url = $(buttons).eq(0).data('url')

                if (!url) {
                    alert('Not found content for Dialog')
                    return;
                }

                this.getTableContent(url);
                this.context.invoke('editor.saveRange');
                this.$ui.showDialog(this.$dialog);
            }
        }
    });
};

module.exports = {
    init: contentItemDialog
};
