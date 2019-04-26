const contentItemDialog = function() {
    const insertContentText = window.editorConfiguration.cms.title;

    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;

            this.initialize = function() {
                const $container = this.options.dialogsInBody ? this.$body : this.editor;
                const loaderTemplate = '<div class="content-item-loader text-center">' +
                    '<img src="/assets/img/cms-loader.gif" />' +
                    '</div>';
                const bodyTemplate = '<div class="content-item-body">' +
                    loaderTemplate +
                    '<div class="content-ajax"></div>' +
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

            this.mapEvents = function () {
                const self = this;
                this.$dialog.find('.add-content-item').on('click', function (event) {
                    event.preventDefault();
                    self.addContent();
                });
            }

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

            this.addContent = function () {
                const $boxTitlteContainer = this.$dialog.find('.ibox-title h5');
                const chooseId = this.$dialog.find('table input:checked').val();
                const $choseIdErrorSelector = this.$dialog.find('.content-errors .item');
                const chooseTemplate = this.$dialog.find('.template-list input:checked').val();
                const $chooseTemplateErrorSelector = this.$dialog.find('.content-errors .template');
                const $twigTemplate = this.$dialog.find('input[name=twigFunctionTemplate]');
                const isHideDialog = chooseId !== undefined && chooseTemplate !== undefined;

                this.clearError($boxTitlteContainer);

                if (isHideDialog) {
                    $($twigTemplate).val("{{ content_banner(" + chooseId + ", '" + chooseTemplate + "'" + ") }}");
                    this.context.invoke('editor.insertText',  $($twigTemplate).val());
                    this.context.invoke('editor.restoreRange');
                    this.$ui.hideDialog(this.$dialog);
                    return;
                }

                if (!chooseId) {
                    this.showError($choseIdErrorSelector, $boxTitlteContainer)
                }

                if (!chooseTemplate) {
                    this.showError($chooseTemplateErrorSelector, $boxTitlteContainer);
                }
            }

            this.getTableContent = function (url) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: "html",
                    context: this,
                    success: function(data) {
                        const dataAjaxUrl = $(data).find('table').data('ajax');
                        this.$dialog.find('.content-item-loader').hide();
                        this.$dialog.find('.content-item-body .content-ajax').append(data);
                        this.$dialog.find('table').DataTable({'ajax': dataAjaxUrl});
                    }
                });
            }

            this.clearContent = function () {
                this.$dialog.find('.content-item-body .content-ajax').empty();
                this.$dialog.find('.content-item-loader').show();
            }

            this.show = function (params, buttons) {
                const url = $(buttons).eq(0).data('url')

                if (!url) {
                    alert('Not found content for Dialog')
                    return;
                }
                
                this.clearContent();
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
