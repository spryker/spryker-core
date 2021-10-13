/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

// polyfill for Promise()
// extend webpack 2 support on IE11 and PhantomJS
require('es6-promise/auto');

var isImplemented = require('get-root-node-polyfill/is-implemented');

if (!isImplemented()) {
    Node.prototype.getRootNode = require('get-root-node-polyfill');
}

// external dependencies
var $ = require('jquery');
require('datatables.net')(window, $);
require('datatables.net-bs')(window, $);
require('datatables.net-buttons')(window, $);
require('datatables.net-buttons-bs')(window, $);
require('datatables.net-responsive')(window, $);
require('datatables.net-select')(window, $);
require('jquery-migrate/dist/jquery-migrate.min');
require('jquery-ui/ui/core');
require('jquery-ui/ui/effect');
require('jquery-ui/ui/effects/effect-highlight');
require('jquery-ui/ui/widget');
require('jquery-ui/ui/widgets/datepicker');
require('jquery-ui/ui/widgets/autocomplete');
require('jquery-ui/ui/widgets/button');
require('jquery-ui/ui/widgets/sortable');
require('jquery-ui/ui/widgets/tooltip');
require('bootstrap-sass');
require('metismenu');
require('pace');
require('@spryker/nestable');
require('select2');
require('codemirror');
require('summernote');
require('sweetalert');

// inspinia
require('../../../Inspinia/inspinia');

// spryker customization
require('../../sass/main.scss');
require('./legacy/fixHeight');
