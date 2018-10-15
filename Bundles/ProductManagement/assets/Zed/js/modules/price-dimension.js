/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');

/**
 * @see \Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd
 * @type {string}
 */
const priceDimension = 'price_dimension';

/**
 * @see \Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\MerchantPriceDimensionForm
 * @type {string}
 */
const attrType = 'type';

$(document).ready(function () {

    $('#showSpecificPrices').click(function () {
        let url = window.location.search;

        $("select[id*='" + priceDimension + "'] option:selected").each(function () {
            let type = $(this).closest('select').attr(attrType);
            url = updateUrl(url, priceDimension + "[type]", type);
            url = updateUrl(url, priceDimension + "[idMerchantRelationship]", $(this).val());
        });

        window.location.search = url;
    });

    $('#showDefaultPrices').click(function () {
        let url = window.location.search;

        $("select[id*='" + priceDimension + "'] option:selected").each(function () {
            url = removeParam(url, priceDimension + "[type]");
            url = removeParam(url, priceDimension + "[idMerchantRelationship]");
        });

        window.location.search = url;
    });
});

function updateUrl(url, paramName, paramValue) {

    if (url.includes(paramName)) {
        return setParam(url, paramName, paramValue);
    }

    return url + '&' + paramName + '=' + paramValue;
}

/**
 * TODO: need fix it
 * http://zed.de.suite-nonsplit.local/product-management/edit?id-product-abstract=219&price_dimension[type]=PRICE_DIMENSION_MERCHANT_RELATIONSHIP&price_dimension[idMerchantRelationship]=5#tab-content-price_and_tax
 */
function setParam(uri, key, val) {
    return uri
        .replace(new RegExp("([?&]" + key + "(?=[=&#]|$)[^#&]*|(?=#|$))"), "&" + key + "=" + encodeURIComponent(val))
        .replace(/^([^?&]+)&/, "$1?");
}

function removeParam(url, sParam)
{
    let sPageURL = decodeURIComponent(url.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    let url = url.split('?')[0]+'?';

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] !== sParam) {
            url = url + sParameterName[0] + '=' + sParameterName[1] + '&'
        }
    }

    return url.substring(0,url.length-1);
}

