/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var DependentSelectBox = require('ZedGuiModules/libs/dependent-select-box');

$(document).ready(function () {
    new DependentSelectBox({
        $trigger: $('#company-business-unit_fk_company'),
        $target: $('#company-business-unit_fk_parent_company_business_unit'),
        requestUrl: '/company-business-unit-gui/suggest',
        requestMethod: 'GET',
        data: {
            _type: 'query',
            page: 1,
        },
        dataKey: 'idCompany',
        responseData: {
            response: 'results',
            value: 'id',
            text: 'text',
        },
    });
});
