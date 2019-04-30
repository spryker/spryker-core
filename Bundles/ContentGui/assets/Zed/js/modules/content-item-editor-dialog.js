/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

const ContentItemDialog = function(dialogTitle) {

    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.contentCache = {};

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
                    dialogTitle +
                    '</button>' +
                    '</div>';

                this.$dialog = this.$ui.dialog({
                    title: dialogTitle,
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
            };


            this.showError = function (errorSelector, container) {
                errorSelector.insertAfter(container);
            };

            this.addContent = function () {
                const $titleHeader = this.$dialog.find('.ibox-title h5');
                const $templateHeader = this.$dialog.find('.template-title');
                const chosenId = this.$dialog.find('table input:checked').val();
                const $choseIdErrorSelector = this.$dialog.find('.content-errors .item');
                const isTemplateListExists = this.$dialog.find('.template-list').length;
                const chosenTemplate = this.$dialog.find('.template-list input:checked').val();
                const $chooseTemplateErrorSelector = this.$dialog.find('.content-errors .template');
                const twigTemplate = this.$dialog.find('input[name=twigFunctionTemplate]').val();
                const readyToInsert = chosenId !== undefined && (!isTemplateListExists || isTemplateListExists && chosenTemplate);

                if (readyToInsert) {
                    let builtText = twigTemplate.replace(/%\w+%/g, function (param) {
                        return {
                            '%ID%': chosenId,
                            '%TEMPLATE%': chosenTemplate
                        }[param];
                    });
                    this.context.invoke('editor.restoreRange');
                    this.context.invoke('editor.insertText', builtText);
                    this.$ui.hideDialog(this.$dialog);
                    return;
                }

                if (!chosenId) {
                    this.showError($choseIdErrorSelector, $titleHeader)
                }

                if (isTemplateListExists && !chosenTemplate) {
                    this.showError($chooseTemplateErrorSelector, $templateHeader);
                }
            };

            this.getTableContent = function (url) {
                const self = this;
                if (!this.contentCache[url]) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: "html",
                        context: this,
                        success: function (data) {
                            self.contentCache[url] = data;
                            self.initContentHtml(data)
                        }
                    });
                } else {
                    this.initContentHtml(this.contentCache[url])
                }
            };

            this.initContentHtml = function (data) {
                const dataAjaxUrl = $(data).find('table').data('ajax');
                this.$dialog.find('.content-item-loader').hide();
                this.$dialog.find('.content-item-body .content-ajax').append(data);
                this.$dialog.find('table').DataTable({'ajax': dataAjaxUrl});
            };

            this.clearContent = function () {
                this.$dialog.find('.content-item-body .content-ajax').empty();
                this.$dialog.find('.content-item-loader').show();
            };

            this.show = function (params, buttons) {
                const url = $(buttons).eq(0).data('url');

                if (!url) {
                    alert('Not found content for Dialog');
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

module.exports = ContentItemDialog;
