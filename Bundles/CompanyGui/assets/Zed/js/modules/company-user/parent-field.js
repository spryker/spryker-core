/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var companyFieldPath = 'select#company-user_fk_company';

function initialize() {
    var companyField = new CompanyFieldHandler();

    companyField.init();
}

function CompanyFieldHandler() {
    var $companyField = $(companyFieldPath);

    function init() {
        $companyField.select2({
            ajax: {
                url: $companyField.attr('data-url'),
                delay: 250,
                dataType: 'json',
                cache: true,
            },
            minimumInputLength: 2,
            multiple: false,
        });
    }

    return {
        init: init,
    };
}

module.exports = {
    initialize: initialize,
};
