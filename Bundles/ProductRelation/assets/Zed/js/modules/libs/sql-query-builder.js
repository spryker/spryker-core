/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('jquery-query-builder');

let SqlQueryBuilder = function(options) {
    this.idProductRelation = null;
    this.builder  = null;
    this.queryBuilderElement = null;
    this.filtersUrl = null;
    this.productRelationQuerySet = null;
    this.productRelationForm = null;
    this.productRelationFormSubmitBtn = null;
    this.ruleQueryTable = null;
    this.queryBuilderElement = null;

    $.extend(this, options);

    let filterConfigurationUrl = this.filtersUrl + this.idProductRelation;

    let self = this;
    $.get(filterConfigurationUrl).done(function(filters) {
        self.builder = self.queryBuilderElement.queryBuilder(
            self.getQueryBuilderOptions(filters)
        );
        self.loadQuerySet();
        self.watchForQueryRuleUpdates();
        self.onFormSubmit();
    });
};

SqlQueryBuilder.prototype.getQuerySet = function() {
    let status = this.builder.queryBuilder('getRules') || {};

    if (!status.rules || !status.rules.length) {
        return [];
    }

    return this.builder.queryBuilder('getRules');
};

SqlQueryBuilder.prototype.loadQuerySet = function() {

    let querySet = this.productRelationQuerySet.val();
    if (querySet.length > 0) {
        this.builder.queryBuilder('setRules', JSON.parse(querySet));
    }
};

SqlQueryBuilder.prototype.onFormSubmit = function()
{
    let self = this;
    this.productRelationFormSubmitBtn.on('click', function(event) {
        event.preventDefault();

        if (!self.builder.queryBuilder('validate')) {
            $('.tabs-container')
                .find('[data-tab-content-id="tab-content-assign-products"]')
                .addClass('error');
            return;
        }

        let json = JSON.stringify(self.getQuerySet());

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
               contains: 'CONTAINS',
               not_contains: 'DOES NOT CONTAIN',
               in: 'is in',
               not_in: 'is not in'
           }
       },
       sqlOperators: {
           contains: { op: 'CONTAINS ?', mod: '{0}' },
           not_contains: { op: 'DOES NOT CONTAIN ?', mod: '{0}' },
           in: { op: 'IS IN ?', sep: ', ' },
           not_in: { op: 'IS NOT IN ?', sep: ', ' }
       },
       sqlRuleOperator: {
           'CONTAINS': function(v) {
               return {
                   val: v,
                   op: 'contains'
               };
           },
           'DOES NOT CONTAIN': function(v) {
               return {
                   val: v,
                   op: 'not_contains'
               };
           },
           'IS IN': function(v) {
               return {
                   val: v,
                   op: 'in'
               };
           },
           'IS NOT IN': function(v) {
               return {
                   val: v,
                   op: 'not_in'
               };
           }
       }
   };
};

SqlQueryBuilder.prototype.watchForQueryRuleUpdates = function()
{
    let self = this;

    this.queryBuilderElement.on('afterDeleteGroup.queryBuilder afterDeleteRule.queryBuilder afterUpdateRuleValue.queryBuilder	afterUpdateRuleFilter.queryBuilder afterUpdateRuleOperator.queryBuilder afterApplyRuleFlags.queryBuilder afterUpdateGroupCondition.queryBuilder', function() {

        let table = self.initializeRuleProductsTable();
        let json = JSON.stringify(self.getQuerySet());

        self.reloadQueryBuilderTable(table, json);
    });

};

SqlQueryBuilder.prototype.initializeRuleProductsTable = function()
{
    return this.ruleQueryTable.DataTable();
};

SqlQueryBuilder.prototype.replaceUrlParam = function(parameter, value, url)
{
    let regex = new RegExp("([?;&])" + parameter + "[^&;]*[;&]?");
    let query = url.replace(regex, "$1").replace(/&$/, '');

    return (query.length > 2 ? query + "&" : "?") + (value ? parameter + "=" + value : '');
};

SqlQueryBuilder.prototype.reloadQueryBuilderTable = function(table, json)
{
    let url = table.ajax.url();
    let newUrl = this.replaceUrlParam('data', json, url);

    table.ajax.url(newUrl).load();
};


module.exports = SqlQueryBuilder;
