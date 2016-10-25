/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');

function init(config) {
    $("#ffSortButton").click(function(){
        window.location = $("#ffSortSelect").val();
    });
}

module.exports = {
    init: init
};
