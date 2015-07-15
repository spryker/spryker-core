<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business\Model;

interface ProductOptionReaderInterface
{

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForConcreteProduct($idProduct, $idLocale);

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idTypeUsage, $idLocale);

    /**
     * @param int $idTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idTypeUsage);

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
    public function getConfigPresetsForConcreteProduct($idProduct);

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset);

    /**
     * @param int $idTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idTypeUsage);

}
