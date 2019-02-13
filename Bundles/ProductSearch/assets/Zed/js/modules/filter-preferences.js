/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

$(document).ready(function() {

    /**
     * Copy translations to other languages on click
     */
    $('.copy-to-other-languages').on('click', function() {
        var sourceTab = $(this).data('sourceTab');
        var sourceInputClass = $(this).data('sourceInputClass');

        $('#' + sourceTab).find('.' + sourceInputClass).each(function(i, input) {
            var valueToCopy = $(input).val();

            $('.tab-translation')
                .not('#' + sourceTab)
                .find('.' + sourceInputClass)
                .eq(i)
                .val(valueToCopy);
        });
    });

});
