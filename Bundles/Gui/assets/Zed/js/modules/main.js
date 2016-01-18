/**
 * 
 * GUI main module (dependencies)
 * @copyright: Spryker Systems GmbH
 *
 */

'use strict';

// external dependencies
require('jquery');
require('jquery-ui-bundle');
require('bootstrap-sass');
require('jquery-slimscroll/jquery.slimscroll');
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

// inspinia
require('../../../Inspinia/inspinia');
require('../../../Inspinia/img/profile_small.jpg');
require('../../../Inspinia/img/dashbard4_1.jpg');
require('../../../Inspinia/img/dashbard4_2.jpg');
require('../../../Inspinia/img/full_height.jpg');
require('../../../Inspinia/img/off_canvas.jpg');

// spryker customization
require('../../sass/main.scss');
require('./legacy/fixHeight');
require('./init');
