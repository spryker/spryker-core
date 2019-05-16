var ContentItemEditorPopover = function () {
    $.extend($.summernote.plugins, {
        'contentItemPopover': function (context) {
            this.context = context;
            this.ui = $.summernote.ui;
            this.note = context.layoutInfo.note;
            this.contentItemEditorSelector = '.js-content-item-editor';
            this.$clickedNode = [null];

            this.events = {
                'summernote.keyup summernote.mouseup summernote.change summernote.scroll': function(event) {
                    this.showPopover(event);
                }.bind(this),
                'summernote.disable summernote.dialog.shown summernote.codeview.toggled': function() {
                    this.hidePopover();
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

                this.$clickedNode = $(this.context.invoke('editor.createRange').sc).parents('.js-content-item-editor');
                var clickedContentItemEditor = this.getClickedContentItemEditor(this.$clickedNode);

                if (!clickedContentItemEditor) {
                    return;
                }

                this.updatePopoverButtons(clickedContentItemEditor);

                var itemPosition = this.popoverPosition(clickedContentItemEditor);

                this.$contentItemPopover.css({
                    display: 'block',
                    left: itemPosition.left,
                    top: itemPosition.top
                });
            };

            this.hidePopover = function() {
                this.$contentItemPopover.hide();
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
            };

            this.updatePopoverButtons = function (clickedContentItemEditor) {
                var itemType = clickedContentItemEditor.dataset.type;
                var itemId = clickedContentItemEditor.dataset.id;
                var itemTemplate = clickedContentItemEditor.dataset.template;
                var $popoverButtons = this.$contentItemPopover.find('button');

                $popoverButtons.each(function () {
                    var button = $(this);
                    button.attr('data-type', itemType);
                    button.attr('data-id', itemId);
                    button.attr('data-template', itemTemplate);
                });
            };

            this.getClickedNode = function() {
                return this.$clickedNode[0];
            };
        }
    });
};

module.exports = ContentItemEditorPopover;
