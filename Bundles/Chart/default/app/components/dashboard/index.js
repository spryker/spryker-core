/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var Plotly = require('plotly.js/lib/core');

Plotly.register([
    require('plotly.js/lib/pie'),
    require('plotly.js/lib/bar'),
    require('plotly.js/lib/scatter')
]);

module.exports = {
    name: 'plotly',
    view: {
        init: function($root) {
            this.$root = $root;
            Plotly.plot($root.elem(), $root.data('data'), $root.data('layout'));
        }
    }
};
