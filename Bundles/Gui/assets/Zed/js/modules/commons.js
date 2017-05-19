/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

// polyfill for Promise()
// extend webpack 2 support on IE11 and PhantomJS
require('es6-promise/auto');

// external dependencies
require('jquery');

if (DEV) {
    require('jquery-migrate');
}

if (!DEV) {
    require('jquery-migrate/dist/jquery-migrate.min');
}

require('jquery-ui');
require('bootstrap-sass');
require('metismenu');
require('pace');
require('datatables.net');
require('datatables.net-bs');
require('datatables.net-buttons');
require('datatables.net-buttons-bs');
require('datatables.net-responsive');
require('datatables.net-select');
require('nestable');
require('select2');
require('codemirror');
require('summernote');
require('sweetalert');

// inspinia
require('../../../Inspinia/inspinia');

// spryker customization
require('../../sass/main.scss');
require('./legacy/fixHeight');
require('./init');
