/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var dataTable = require('ZedGuiModules/libs/data-table');

var ContentItemDialog = function(
    dialogTitle,
    dialogContentUrl,
    insertButtonTitle,
    widgetHtmlTemplate,
    maxWidgetNumber
) {
    $.extend($.summernote.plugins, {
        'contentItemDialog': function (context) {
            this.context = context;
            this.$body = $(document.body);
            this.editor = context.layoutInfo.editor;
            this.options = context.options;
            this.$ui = $.summernote.ui;
            this.$range = $.summernote.range;
            this.history = context.modules.editor.history;
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
                var chosenType = this.$dialog.find('input[name=type]').val();
                var chosenDisplayType = this.$dialog.find('input[name=displayType]').val();
                var chosenName = checkedContentItem.data('content-item-name');
                var chosenId = this.$dialog.find('table input:checked').data('id');
                var chosenKey = this.$dialog.find('table input:checked').val();
                var $choseIdErrorSelector = this.$dialog.find('.content-errors .item');
                var isTemplateListExists = this.$dialog.find('.template-list').length;
                var chosenTemplate = this.$dialog.find('.template-list input:checked').data('template');
                var chosenTemplateIdentifier = this.$dialog.find('.template-list input:checked').val();
                var $chooseTemplateErrorSelector = this.$dialog.find('.content-errors .template');
                var twigTemplate = this.$dialog.find('input[name=twigFunctionTemplate]').val();
                var readyToInsert = chosenKey !== undefined && (!isTemplateListExists || isTemplateListExists && chosenTemplate);

                if (readyToInsert) {
                    if ($('span[data-twig-expression*="{{ content_"]').length > maxWidgetNumber) {
                        alert('Limit exceeded, maximum number of widgets ' + maxWidgetNumber);
                        return;
                    }

                    this.context.invoke('editor.restoreRange');
                    this.$ui.hideDialog(this.$dialog);

                    var elementForInsert = this.getNewDomElement(
                        twigTemplate,
                        chosenId,
                        chosenKey,
                        chosenType,
                        chosenDisplayType,
                        chosenName,
                        chosenTemplate,
                        chosenTemplateIdentifier,
                        widgetHtmlTemplate
                    );
                    this.addItemInEditor(elementForInsert);
                }

                if (!chosenKey) {
                    this.showError($choseIdErrorSelector, $titleHeader)
                }

                if (isTemplateListExists && !chosenTemplate) {
                    this.showError($chooseTemplateErrorSelector, $templateHeader);
                }
            };

            this.updateElementForInsert = function ($clickedNode, elementForInsert) {
                if (this.isNodeEmpty($clickedNode)) {
                    this.clearNode($clickedNode);

                    return elementForInsert;
                }

                return '<p>' + elementForInsert + '</p>';
            };

            this.addItemInEditor = function (elementForInsert) {
                var $clickedNode = this.context.invoke('contentItemPopover.getClickedNode');

                if ($clickedNode.length) {
                    this.clearNode($clickedNode);
                }

                if (!$clickedNode.length) {
                    $clickedNode = $(this.context.invoke('editor.createRange').sc);
                    elementForInsert = this.updateElementForInsert($clickedNode, elementForInsert);
                }

                this.context.invoke('pasteHTML', elementForInsert);
                this.removeUnecessaryLines($clickedNode);
            };

            this.isNodeEmpty = function ($clickedNode) {
                var $nodeInnerItems = $clickedNode.children();

                return $nodeInnerItems.length <= 1 && $nodeInnerItems.eq(0).is('br'); // Empty node in summernote consider <br> tag
            };

            this.isWidgetEmpty = function ($clickedNode) {
                var $nodeInnerItems = $clickedNode.children();

                if (!$nodeInnerItems.eq(0).is('.js-content-item-editor')) {
                    return false;
                }

                return $nodeInnerItems.length <= 2 && $nodeInnerItems.eq(1).is('br') && $nodeInnerItems.children().length <= 1;
            };

            this.removeItemFromEditor = function () {
                var $clickedNode = this.context.invoke('contentItemPopover.getClickedNode');

                this.clearNode($clickedNode);
                this.context.invoke('contentItemPopover.hidePopover');
                this.context.invoke('pasteHTML', ' ');
            };

            this.removeUnecessaryLines = function ($clickedNode) {
                $clickedNode = $clickedNode.is('p') ? $clickedNode : $clickedNode.parents('p');
                var self = this;
                var $insertedNode = $clickedNode.next();
                var $nextNode = $insertedNode.next();

                if (this.isWidgetEmpty($nextNode) || this.isNodeEmpty($nextNode)) {
                    $insertedNode.removeAttr('style');
                    $nextNode.remove();
                    self.history.stackOffset--;
                    self.history.stack.splice(-1,1);
                    self.history.recordUndo();
                };
            };

            this.clearNode = function ($clickedNode) {
                $clickedNode = $clickedNode.is('p') ? $clickedNode : $clickedNode.parents('p');

                $clickedNode.empty();
            };

            this.getNewDomElement = function (
                twigTemplate,
                id,
                key,
                type,
                displayType,
                contentName,
                templateName,
                templateIdentifier,
                widgetHtmlTemplate
            ) {
                var twigExpression = twigTemplate.replace(/%\w+%/g, function (param) {
                    return {
                        '%KEY%': key,
                        '%TEMPLATE%': templateIdentifier
                    }[param];
                });

                var builtTemplate = widgetHtmlTemplate.replace(/%\w+%/g, function (param) {
                    return {
                        '%TYPE%': type,
                        '%DISPLAY_TYPE%': displayType,
                        '%KEY%': key,
                        '%ID%': id,
                        '%NAME%': contentName,
                        '%TEMPLATE_DISPLAY_NAME%': templateName,
                        '%TEMPLATE%': templateIdentifier,
                        '%TWIG_EXPRESSION%': twigExpression,
                    }[param];
                });

                return builtTemplate;
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
                    'lengthChange': false,
                    'language': dataTable.defaultConfiguration.language
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

                if (dataset.hasOwnProperty('key')) {
                    urlParams.contentKey = dataset.key;
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
