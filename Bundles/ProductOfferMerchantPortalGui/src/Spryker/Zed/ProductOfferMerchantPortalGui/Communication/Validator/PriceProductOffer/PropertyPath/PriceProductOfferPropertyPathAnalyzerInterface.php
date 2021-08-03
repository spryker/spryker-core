<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath;

interface PriceProductOfferPropertyPathAnalyzerInterface
{
    /**
     * @param string $propertyPath
     *
     * @return string
     */
    public function transformPropertyPathToColumnId(string $propertyPath): string;

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isRowViolation(string $propertyPath): bool;

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isVolumePriceViolation(string $propertyPath): bool;

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isPriceRowError(string $propertyPath): bool;

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isVolumePriceRowError(string $propertyPath): bool;

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getPriceProductOfferIndex(string $propertyPath): int;

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getPriceProductIndex(string $propertyPath): int;

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getVolumePriceIndex(string $propertyPath): int;
}
