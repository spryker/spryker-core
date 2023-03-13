/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('@spryker/jquery-query-builder');

var SqlQueryBuilder = function (options) {
    this.idProductRelation = null;
    this.builder = null;
    this.queryBuilderElement = null;
    this.filtersUrl = null;
    this.productRelationQuerySet = null;
    this.productRelationForm = null;
    this.productRelationFormSubmitBtn = null;
    this.ruleQueryTable = null;

    $.extend(this, options);

    var filterConfigurationUrl = this.filtersUrl + this.idProductRelation;

    var self = this;
    $.get(filterConfigurationUrl).done(function (filters) {
        self.builder = self.queryBuilderElement.queryBuilder(self.getQueryBuilderOptions(filters));
        self.loadQuerySet();
        self.watchForQueryRuleUpdates();
        self.updateTable();
        self.onFormSubmit();
    });
};

SqlQueryBuilder.prototype.getQuerySet = function () {
    var status = this.builder.queryBuilder('getRules') || {};

    if (!status.rules || !status.rules.length) {
        return [];
    }

    this.enableSubmitButton();

    return this.builder.queryBuilder('getRules');
};

SqlQueryBuilder.prototype.loadQuerySet = function () {
    var querySet = this.productRelationQuerySet.val();
    if (querySet.length > 0) {
        this.builder.queryBuilder('setRules', JSON.parse(querySet));
    }
};

SqlQueryBuilder.prototype.onFormSubmit = function () {
    var self = this;
    this.productRelationFormSubmitBtn.on('click', function (event) {
        if (!self.builder.queryBuilder('validate')) {
            event.preventDefault();
            $('.tabs-container').find('[data-tab-content-id="tab-content-assign-products"]').addClass('error');

            $('.flash-messages').html('<div class="alert alert-danger">Query rule not provided.</div>');

            return;
        }
    });
};

SqlQueryBuilder.prototype.getQueryBuilderOptions = function (filters) {
    return {
        filters: filters,
        default_condition: 'AND',
        optgroups: {
            attributes: '-- Attributes',
        },
        lang: {
            operators: {
                in: 'is in',
            },
        },
        sqlOperators: {
            in: { op: 'IS IN ?', sep: ', ' },
        },
        sqlRuleOperator: {
            'IS IN': function (v) {
                return {
                    val: v,
                    op: 'in',
                };
            },
        },
    };
};

SqlQueryBuilder.prototype.watchForQueryRuleUpdates = function () {
    var self = this;

    this.queryBuilderElement.on(
        'afterAddGroup.queryBuilder afterAddRule.queryBuilder afterUpdateRuleValue.queryBuilder	afterUpdateRuleFilter.queryBuilder afterUpdateRuleOperator.queryBuilder afterApplyRuleFlags.queryBuilder afterUpdateGroupCondition.queryBuilder afterDeleteRule.queryBuilder afterDeleteGroup.queryBuilder',
        function () {
            self.updateTable();
            self.updateQuerySetField();
        },
    );
};

SqlQueryBuilder.prototype.updateTable = function () {
    var table = this.initializeRuleProductsTable();
    var json = JSON.stringify(this.getQuerySet());

    this.reloadQueryBuilderTable(table, json);
};

SqlQueryBuilder.prototype.updateQuerySetField = function () {
    var json = JSON.stringify(this.getQuerySet());

    this.productRelationQuerySet.val(json);
};

SqlQueryBuilder.prototype.initializeRuleProductsTable = function () {
    return this.ruleQueryTable.DataTable();
};

SqlQueryBuilder.prototype.replaceUrlParam = function (parameter, value, url) {
    var regex = new RegExp('([?;&])' + parameter + '[^&;]*[;&]?');
    var query = url.replace(regex, '$1').replace(/&$/, '');

    return (query.length > 2 ? query + '&' : '?') + (value ? parameter + '=' + value : '');
};

SqlQueryBuilder.prototype.reloadQueryBuilderTable = function (table, json) {
    var url = table.ajax.url();
    var newUrl = this.replaceUrlParam('data', json, url);

    table.ajax.url(newUrl).load();
};

SqlQueryBuilder.prototype.enableSubmitButton = function () {
    this.productRelationFormSubmitBtn[0].disabled = false;
    this.productRelationFormSubmitBtn[0].classList.remove('disabled');
};

module.exports = SqlQueryBuilder;
