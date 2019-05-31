/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var ContentItemDialog = function(
    dialogTitle,
    dialogContentUrl,
    insertButtonTitle,
    widgetHtmlTemplate
) {
    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.$range = $.summernote.range;
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
                var checkedContentItem = this.$dialog.find('table input:checked');
                var chosenId = checkedContentItem.val();
                var chosenType = checkedContentItem.data('content-item-type');
                var chosenName = checkedContentItem.data('content-item-name');
                var $choseIdErrorSelector = this.$dialog.find('.content-errors .item');
                var isTemplateListExists = this.$dialog.find('.template-list').length;
                var chosenTemplate = this.$dialog.find('.template-list input:checked').data('template');
                var chosenTemplateIdentifier = this.$dialog.find('.template-list input:checked').val();
                var $chooseTemplateErrorSelector = this.$dialog.find('.content-errors .template');
                var twigTemplate = this.$dialog.find('input[name=twigFunctionTemplate]').val();
                var readyToInsert = chosenId !== undefined && (!isTemplateListExists || isTemplateListExists && chosenTemplate);

                if (readyToInsert) {
                    var elementForInsert = this.getNewDomElement(
                        twigTemplate,
                        chosenId,
                        chosenType,
                        chosenName,
                        chosenTemplate,
                        chosenTemplateIdentifier,
                        widgetHtmlTemplate
                    );

                    this.$ui.hideDialog(this.$dialog);
                    this.context.invoke('editor.restoreRange');

                    if ($('span[data-twig-expression*="{{ content_"]').length > 10000) {
                        alert('Widget not added, limit exceeded, maximum number of widgets 10000');
                        return;
                    }

                    this.addItemInEditor(elementForInsert);
                }

                if (!chosenId) {
                    this.showError($choseIdErrorSelector, $titleHeader)
                }

                if (isTemplateListExists && !chosenTemplate) {
                    this.showError($chooseTemplateErrorSelector, $templateHeader);
                }
            };

            this.addItemInEditor = function (elementForInsert) {
                var $clickedNode = this.context.invoke('contentItemPopover.getClickedNode');

                if ($clickedNode.length) {
                    this.clearNode($clickedNode);
                } else {
                    var $existingRange = this.context.invoke('editor.createRange');
                    $($existingRange.sc).parents('p').empty();
                }

                this.context.invoke('insertNode', elementForInsert);
            };

            this.removeItemFromEditor = function () {
                var $clickedNode = this.context.invoke('contentItemPopover.getClickedNode');

                this.clearNode($clickedNode);
                this.context.invoke('contentItemPopover.hidePopover');
                this.context.invoke('pasteHTML', ' ');
            };

            this.clearNode = function ($clickedNode) {
                var $clickedNodeParent = $clickedNode.parent('p');

                $clickedNodeParent.empty();
            };

            this.getNewDomElement = function (
                twigTemplate,
                id,
                type,
                contentName,
                templateName,
                templateIdentifier,
                widgetHtmlTemplate
            ) {
                var twigExpression = twigTemplate.replace(/%\w+%/g, function (param) {
                    return {
                        '%ID%': id,
                        '%TEMPLATE%': templateIdentifier
                    }[param];
                });

                var builtTemplate = widgetHtmlTemplate.replace(/%\w+%/g, function (param) {
                    return {
                        '%TYPE%': type,
                        '%ID%': id,
                        '%NAME%': contentName,
                        '%TEMPLATE_DISPLAY_NAME%': templateName,
                        '%TEMPLATE%': templateIdentifier,
                        '%TWIG_EXPRESSION%': twigExpression,
                    }[param];
                });

                return $.parseHTML(builtTemplate.trim())[0];
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
                this.isCreateNew = dataset.hasOwnProperty('new');

                if (!dataset.hasOwnProperty('type')) {
                    return;
                }

                var urlParams = { type: dataset.type };

                if (dataset.hasOwnProperty('id')) {
                    urlParams.idContent = dataset.id;
                }

                if (dataset.hasOwnProperty('template')) {
                    urlParams.template = dataset.template;
                }

                var url = dialogContentUrl + '?' + $.param(urlParams);

                this.context.invoke('editor.saveRange');
                this.clearContent();
                this.getDialogContent(url);
                this.$ui.showDialog(this.$dialog);
            };
        }
    });
};

module.exports = ContentItemDialog;
