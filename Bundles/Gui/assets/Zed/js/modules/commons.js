/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

// external dependencies
const $ = require('jquery');
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
window.CodeMirror = require('codemirror');
require('codemirror/mode/htmlmixed/htmlmixed.js');
require('summernote');
require('sweetalert');

XMLHttpRequest.prototype = Object.getPrototypeOf(new XMLHttpRequest());

// inspinia
require('../../../Inspinia/inspinia');

// spryker customization
require('../../sass/main.scss');

require('./legacy/fixHeight');
