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
