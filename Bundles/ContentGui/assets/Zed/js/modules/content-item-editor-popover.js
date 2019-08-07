var ContentItemEditorPopover = function () {
    $.extend($.summernote.plugins, {
        'contentItemPopover': function (context) {
            this.context = context;
            this.ui = $.summernote.ui;
            this.note = context.layoutInfo.note;
            this.$range = $.summernote.range;
            this.contentItemEditorSelector = '.js-content-item-editor';
            this.$clickedNode = [];
            this.isPopoverVisible = false;

            this.events = {
                'summernote.keyup summernote.mouseup summernote.change': function() {
                    this.$clickedNode = $(this.context.invoke('editor.createRange').sc).parents('.js-content-item-editor');
                    this.showPopover();
                }.bind(this),
                'summernote.disable summernote.dialog.shown summernote.codeview.toggled': function() {
                    this.hidePopover();
                }.bind(this),
                'summernote.scroll': function (event) {
                    if (this.isPopoverVisible) {
                        this.scrollHandler(event);
                    }
                }.bind(this)
            };

            this.initialize = function() {
                this.$contentItemPopover = this.ui.popover({
                    className: 'note-link-popover'
                }).render().appendTo(context.options.container);

                var $content = this.$contentItemPopover.find('.popover-content,.note-popover-content');

                this.context.invoke('buttons.build', $content, this.context.options.popover.editContentItem);
            };

            this.showPopover = function () {
                this.hidePopover();

                var clickedContentItemEditor = this.getClickedContentItemEditor(this.$clickedNode);

                if (!clickedContentItemEditor) {
                    return;
                }

                this.isPopoverVisible = true;
                this.updatePopoverButtons(clickedContentItemEditor);
                this.putCarretInTheBegining(clickedContentItemEditor);

                var itemPosition = this.getPopoverPosition(clickedContentItemEditor);

                this.$contentItemPopover.css({
                    display: 'block',
                    left: itemPosition.left,
                    top: itemPosition.top
                });
            };

            this.hidePopover = function() {
                this.$contentItemPopover.hide();
                this.isPopoverVisible = false;
            };

            this.scrollHandler = function (event) {
                this.showPopover();

                var $editor = $(event.currentTarget.nextSibling).find('.note-editing-area');
                var editorPositionTop = $editor.offset().top;
                var editorPositionBottom = editorPositionTop + $editor.height();
                var popoverPositionTop = this.$contentItemPopover.offset().top;
                var popoverPositionBottom = popoverPositionTop + this.$contentItemPopover.height();

                if (popoverPositionBottom > editorPositionBottom || popoverPositionTop < editorPositionTop) {
                    this.hidePopover();
                }
            };

            this.putCarretInTheBegining = function (clickedContentItemEditor) {
                var itemParentNode = this.$range.createFromNode(clickedContentItemEditor.parentNode)

                itemParentNode.collapse(true);
                itemParentNode.select();
            };

            this.getPopoverPosition = function (placeholder) {
                var $placeholder = $(placeholder);
                var position = $placeholder.offset();
                var height = $placeholder.outerHeight(true);

                return {
                    left: position.left,
                    top: position.top + height
                };
            };

            this.getClickedContentItemEditor = function ($clickedNode) {
                if ($clickedNode.is(this.contentItemEditorSelector)) {
                    return $clickedNode[0];
                }

                var $parentContentItemEditor = $clickedNode.parents(this.contentItemEditorSelector);

                if ($parentContentItemEditor.length) {
                    return $parentContentItemEditor[0];
                }

                return false;
            };

            this.updatePopoverButtons = function (clickedContentItemEditor) {
                var itemType = clickedContentItemEditor.dataset.type;
                var itemId = clickedContentItemEditor.dataset.id;
                var itemKey = clickedContentItemEditor.dataset.key;
                var itemTemplate = clickedContentItemEditor.dataset.template;
                var $popoverButtons = this.$contentItemPopover.find('button');

                $popoverButtons.each(function () {
                    var button = $(this);
                    button.attr('data-type', itemType);
                    button.attr('data-key', itemKey);
                    button.attr('data-id', itemId);
                    button.attr('data-template', itemTemplate);
                });
            };

            this.getClickedNode = function() {
                return this.$clickedNode;
            };
        }
    });
};

module.exports = ContentItemEditorPopover;
