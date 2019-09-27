/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../sass/main.scss');

var ProductSelector = require('./libs/product-selector');
var SqlQueryBuilder = require('./libs/sql-query-builder');

$(document).ready(function() {

    new ProductSelector({
        idProductAbstractElement: $('#product_relation_fkProductAbstract'),
        selectedProductContainer: $('#selected-product'),
        selectProductNotice: $('#select-product-notice'),
        productTable: $('#product-table'),
        selectProductUrl: '/product-relation/product-selector?id-product-abstract='
    });

    new SqlQueryBuilder({
        id: $('#product_relation_idProductRelation').val(),
        queryBuilderElement: $('#builder'),
        filtersUrl: '/product-relation/query-builder/load-filter-set?id-product-relation=',
        productRelationQuerySet: $('#product_relation_querySet'),
        productRelationForm: $('#form-product-relation'),
        productRelationFormSubmitBtn: $('#submit-relation'),
        ruleQueryTable: $('#rule-query-table')
    });

});
