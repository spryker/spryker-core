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
const priceDimensionParam = 'price-dimension';

/**
 * @type {string}
 */
const selectIdPath = '#price-dimension-merchant-relationship';

$(document).ready(function () {

    let selectedValue = parseInt(getUrlParam(priceDimensionParam + "[idMerchantRelationship]", 0));

    if (selectedValue) {
        $(selectIdPath + " option[value='" + selectedValue + "']").attr('selected', 'selected');
    }

    $(selectIdPath).change(function() {
        if (!$(this).val()) {
            removeParamsFromUrl()
        } else {
            addNewParamsToUrl();
        }
    });

    function addNewParamsToUrl() {

        let url = window.location.search;

        $(selectIdPath + " option:selected").each(function () {
            url = removeParam(priceDimensionParam + "[type]", url);
            url = updateUrl(url, priceDimensionParam + "[type]", $(this).closest('select').attr('data-type'));

            url = removeParam(priceDimensionParam + "[idMerchantRelationship]", url);
            url = updateUrl(url, priceDimensionParam + "[idMerchantRelationship]", $(this).val());
        });

        window.location.search = url;
    }

    function removeParamsFromUrl() {
        let url = window.location.search;

        url = removeParam(priceDimensionParam + "[type]", url);
        url = removeParam(priceDimensionParam + "[idMerchantRelationship]", url);

        window.location.search = url;
    }

    function updateUrl(url, paramName, paramValue) {

        if (url.includes(paramName)) {
            return setParam(url, paramName, paramValue);
        }

        return url + '&' + paramName + '=' + paramValue;
    }

    function setParam(uri, key, val) {
        return uri
            .replace(new RegExp("([?&]" + key + "(?=[=&#]|$)[^#&]*|(?=#|$))"), "&" + key + "=" + encodeURIComponent(val))
            .replace(/^([^?&]+)&/, "$1?");
    }

    function removeParam(key, sourceURL) {
        let rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (let i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    }

    function getUrlParam(parameter, defaultvalue) {
        let urlparameter = defaultvalue;

        if (window.location.href.indexOf(parameter) > -1) {
            urlparameter = getUrlVars()[parameter];
        }

        return urlparameter;
    }

    function getUrlVars() {
        let vars = {};
        let parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });

        return vars;
    }
});