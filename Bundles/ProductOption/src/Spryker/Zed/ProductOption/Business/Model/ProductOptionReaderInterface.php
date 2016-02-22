<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

interface ProductOptionReaderInterface
{

    /**
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $idLocale);

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForProductConcrete($idProduct, $idLocale);

    /**
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idProductOptionTypeUsage);

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage);

    /**
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator);

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForProductConcrete($idProduct);

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset);

    /**
     * @param int $idProductAttributeTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage);

}
