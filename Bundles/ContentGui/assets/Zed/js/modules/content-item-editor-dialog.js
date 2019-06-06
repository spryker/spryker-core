/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ContentItemDialog = function(dialogTitle, dialogContentUrl, insertButtonTitle) {

    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.contentCache = {};

            this.initialize = function() {
                var $container = this.options.dialogsInBody ? this.$body : this.editor;
                var loaderTemplate = '<div class="content-item-loader text-center">' +
                    '<img src="/assets/img/cms-loader.gif" />' +
                    '</div>';
                var bodyTemplate = '<div class="content-item-body">' +
                    loaderTemplate +
                    '<div class="content-ajax"></div>' +
                    '</div>';

                var footerTemplate = '<div class="content-item-footer">' +
                    '<button class="btn btn-primary note-btn note-btn-primary add-content-item">' +
                    insertButtonTitle +
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
                var self = this;
                this.$dialog.find('.add-content-item').on('click', function (event) {
                    event.preventDefault();
                    self.addContent();
                });
            };


            this.showError = function (errorSelector, container) {
                errorSelector.insertAfter(container);
            };

            this.addContent = function () {
                var $titleHeader = this.$dialog.find('.ibox-title h5');
                var $templateHeader = this.$dialog.find('.template-title');
                var chosenKey = this.$dialog.find('table input:checked').val();
                var $choseIdErrorSelector = this.$dialog.find('.content-errors .item');
                var isTemplateListExists = this.$dialog.find('.template-list').length;
                var chosenTemplate = this.$dialog.find('.template-list input:checked').val();
                var $chooseTemplateErrorSelector = this.$dialog.find('.content-errors .template');
                var twigTemplate = this.$dialog.find('input[name=twigFunctionTemplate]').val();
                var readyToInsert = chosenKey !== undefined && (!isTemplateListExists || isTemplateListExists && chosenTemplate);

                if (readyToInsert) {
                    var builtText = twigTemplate.replace(/%\w+%/g, function (param) {
                        return {
                            '%ID%': chosenKey,
                            '%TEMPLATE%': chosenTemplate
                        }[param];
                    });
                    this.context.invoke('editor.restoreRange');
                    this.context.invoke('editor.insertText', builtText);
                    this.$ui.hideDialog(this.$dialog);
                    return;
                }

                if (!chosenKey) {
                    this.showError($choseIdErrorSelector, $titleHeader)
                }

                if (isTemplateListExists && !chosenTemplate) {
                    this.showError($chooseTemplateErrorSelector, $templateHeader);
                }
            };

            this.getDialogContent = function (url) {
                var self = this;
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
                var dataAjaxUrl = $(data).find('table').data('ajax');
                this.$dialog.find('.content-item-loader').hide();
                this.$dialog.find('.content-item-body .content-ajax').append(data);
                this.$dialog.find('table').DataTable({
                    'ajax': dataAjaxUrl,
                    'lengthChange': false
                });
            };

            this.clearContent = function () {
                this.$dialog.find('.content-item-body .content-ajax').empty();
                this.$dialog.find('.content-item-loader').show();
            };

            this.show = function (value, target) {
                var dataset = target[0].dataset;
                if (!dataset.hasOwnProperty('type')) {
                    return;
                }

                var urlParams = {type: dataset.type};
                var url = dialogContentUrl + '?' + $.param(urlParams);

                this.clearContent();
                this.getDialogContent(url);
                this.context.invoke('editor.saveRange');
                this.$ui.showDialog(this.$dialog);
            }
        }
    });
};

module.exports = ContentItemDialog;
