/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

SprykerAjax.glossaryKeyUniqueCheck = function () {
    var obj = $('#form_glossary_key').val();
    this.setUrl('/glossary/key/ajax').ajaxSubmit({ term: obj }, function (ajaxResponse) {
        var changed = false;
        $.each(ajaxResponse, function (i, item) {
            var key = '#form_locale_' + i;

            var value = $(key).val();
            if (!value.length) {
                $(key).val(item);
                changed = true;
            }
        });

        if (changed) {
            $('#form_submit').text('Save');
        }
    });
};

$(document).ready(function () {
    $('#form_glossary_key').blur(function () {
        SprykerAjax.glossaryKeyUniqueCheck();
    });
});
