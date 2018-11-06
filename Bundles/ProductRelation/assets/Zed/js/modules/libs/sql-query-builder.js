/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jquery-query-builder');

var SqlQueryBuilder = function(options) {

    this.idProductRelation = null;
    this.builder  = null;
    this.queryBuilderElement = null;
    this.filtersUrl = null;
    this.productRelationQuerySet = null;
    this.productRelationForm = null;
    this.productRelationFormSubmitBtn = null;
    this.ruleQueryTable = null;

    $.extend(this, options);

    var filterConfigurationUrl = this.filtersUrl + this.idProductRelation;

    var self = this;
    $.get(filterConfigurationUrl).done(function(filters) {
        self.builder = self.queryBuilderElement.queryBuilder(
            self.getQueryBuilderOptions(filters)
        );
        self.loadQuerySet();
        self.watchForQueryRuleUpdates();
        self.updateTable()
        self.onFormSubmit();
    });
};

SqlQueryBuilder.prototype.getQuerySet = function() {
    var status = this.builder.queryBuilder('getRules') || {};

    if (!status.rules || !status.rules.length) {
        return [];
    }

    return this.builder.queryBuilder('getRules');
};

SqlQueryBuilder.prototype.loadQuerySet = function() {

    var querySet = this.productRelationQuerySet.val();
    if (querySet.length > 0) {
        this.builder.queryBuilder('setRules', JSON.parse(querySet));
    }
};

SqlQueryBuilder.prototype.onFormSubmit = function()
{
    var self = this;
    this.productRelationFormSubmitBtn.on('click', function(event) {
        event.preventDefault();

        if (!self.builder.queryBuilder('validate')) {
            $('.tabs-container')
                .find('[data-tab-content-id="tab-content-assign-products"]')
                .addClass('error');

            $('.flash-messages').html('<div class="alert alert-danger">Query rule not provided.</div>');

            return;
        }

        var json = JSON.stringify(self.getQuerySet());

        self.productRelationQuerySet.val(json);
        self.productRelationForm.submit();
    });

};

SqlQueryBuilder.prototype.getQueryBuilderOptions = function(filters)
{
   return {
       filters: filters,
       default_condition: 'AND',
       optgroups: {
           attributes: '-- Attributes'
       },
       lang: {
           operators: {
               in: 'is in'
           }
       },
       sqlOperators: {
           in: { op: 'IS IN ?', sep: ', ' }
       },
       sqlRuleOperator: {
           'IS IN': function(v) {
               return {
                   val: v,
                   op: 'in'
               };
           }
       }
   };
};

SqlQueryBuilder.prototype.watchForQueryRuleUpdates = function()
{
    var self = this;

    this.queryBuilderElement.on('afterAddGroup.queryBuilder afterAddRule.queryBuilder afterUpdateRuleValue.queryBuilder	afterUpdateRuleFilter.queryBuilder afterUpdateRuleOperator.queryBuilder afterApplyRuleFlags.queryBuilder afterUpdateGroupCondition.queryBuilder afterDeleteRule.queryBuilder afterDeleteGroup.queryBuilder', function() {
        self.updateTable();
    });

};

SqlQueryBuilder.prototype.updateTable = function()
{
    var table = this.initializeRuleProductsTable();
    var json = JSON.stringify(this.getQuerySet());

    this.reloadQueryBuilderTable(table, json);
}

SqlQueryBuilder.prototype.initializeRuleProductsTable = function()
{
    return this.ruleQueryTable.DataTable();
};

SqlQueryBuilder.prototype.replaceUrlParam = function(parameter, value, url)
{
    var regex = new RegExp("([?;&])" + parameter + "[^&;]*[;&]?");
    var query = url.replace(regex, "$1").replace(/&$/, '');

    return (query.length > 2 ? query + "&" : "?") + (value ? parameter + "=" + value : '');
};

SqlQueryBuilder.prototype.reloadQueryBuilderTable = function(table, json)
{
    var url = table.ajax.url();
    var newUrl = this.replaceUrlParam('data', json, url);

    table.ajax.url(newUrl).load();
};


module.exports = SqlQueryBuilder;
