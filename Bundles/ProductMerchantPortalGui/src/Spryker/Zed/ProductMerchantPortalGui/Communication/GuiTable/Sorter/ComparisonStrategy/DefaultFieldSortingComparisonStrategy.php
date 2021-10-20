<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy;

use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Laminas\Filter\Word\UnderscoreToCamelCase;

class DefaultFieldSortingComparisonStrategy implements PriceProductSortingComparisonStrategyInterface
{
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @var string
     */
    protected const PREFIX_GETTER_METHOD = 'get';

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isApplicable(string $fieldName): bool
    {
        return true;
    }

    /**
     * @param string $fieldName
     *
     * @return callable
     */
    public function getValueExtractorFunction(string $fieldName): callable
    {
        /** @var string $camelCaseFieldName */
        $camelCaseFieldName = (new UnderscoreToCamelCase())->filter($fieldName);

        return function (PriceProductTableViewTransfer $priceProductTableViewTransfer) use ($camelCaseFieldName) {
            $getterMethodName = static::PREFIX_GETTER_METHOD . $camelCaseFieldName;

            if (!method_exists($priceProductTableViewTransfer, $getterMethodName)) {
                return null;
            }

            return $priceProductTableViewTransfer->{$getterMethodName}();
        };
    }
}
