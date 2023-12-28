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
    this.productRelationFormSubmitBtn = null;
    this.ruleQueryTable = null;
    this.tabsContainer = null;
    this.flashMessages = null;

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

    this.toggleSubmitButton(false);
    this.toggleErrorState(false);

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
            self.toggleSubmitButton(true);
            self.toggleErrorState(true);
            window.scrollTo(0, 0);

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

SqlQueryBuilder.prototype.toggleSubmitButton = function (isDisabled) {
    this.productRelationFormSubmitBtn[0].disabled = isDisabled;
    this.productRelationFormSubmitBtn[0].classList.toggle('disabled', isDisabled);
};

SqlQueryBuilder.prototype.toggleErrorState = function (isError) {
    this.tabsContainer.find('[data-tab-content-id="tab-content-assign-products"]').toggleClass('error', isError);
    this.flashMessages.html(
        isError ? '<div class="alert alert-danger">' + this.builder.attr('data-error-message') + '</div>' : '',
    );
};

module.exports = SqlQueryBuilder;
