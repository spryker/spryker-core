/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('./legacy/logic');

$(document).ready( function () {
    $('[name=translation]').on('submit', function() {
        var self = $(this);

        self.find('.html-editor').each(function (index, element) {
            var editor = $(element);

            if (editor.summernote('codeview.isActivated')) {
                editor.summernote('codeview.deactivate');
            }

            if (editor.summernote('isEmpty')) {
                editor.val(null);
            }
        });
    });
});
