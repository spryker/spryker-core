var ContentItemEditorPopover = function () {
    $.extend($.summernote.plugins, {
        'contentItemPopover': function (context) {
            this.context = context;
            this.ui = $.summernote.ui;
            this.note = context.layoutInfo.note;
            this.contentItemEditorSelector = '.js-content-item-editor';

            this.events = {
                'summernote.keyup summernote.mouseup summernote.change summernote.scroll': function() {
                    this.showPopover();
                }.bind(this),
                'summernote.disable summernote.dialog.shown': function() {
                    this.hidePopover();
                }.bind(this)
            };

            this.initialize = function() {
                this.contentItemPopover = this.ui.popover({
                    className: 'note-link-popover'
                }).render().appendTo(context.options.container);

                var $content = this.contentItemPopover.find('.popover-content,.note-popover-content');

                this.context.invoke('buttons.build', $content, this.context.options.popover.editContentItem);
            };

            this.showPopover = function () {
                this.hidePopover();

                var $clickedNode = $(this.context.invoke('editor.createRange').sc.parentElement);
                var clickedContentItemEditor = this.getClickedContentItemEditor($clickedNode);

                if (!clickedContentItemEditor) {
                    return;
                }

                var itemPosition = this.popoverPosition(clickedContentItemEditor);

                this.contentItemPopover.css({
                    display: 'block',
                    left: itemPosition.left,
                    top: itemPosition.top
                });
            };

            this.hidePopover = function() {
                this.contentItemPopover.hide();
            };

            this.popoverPosition = function (placeholder) {
                var $placeholder = $(placeholder);
                var pos = $placeholder.offset();
                var height = $placeholder.outerHeight(true);

                return {
                    left: pos.left,
                    top: pos.top + height
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
            }
        }
    });
};

module.exports = ContentItemEditorPopover;
