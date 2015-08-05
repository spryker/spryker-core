<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Service;


interface ProductClientInterface
{
    /**
     * @param integer $idAbstractProduct
     * @param string $locale
     * @return array
     */
    public function getAbstractProductFromStorageById($idAbstractProduct, $locale);

    /**
     * @param integer $idAbstractProduct
     *
     * @return array
     */
    public function getAbstractProductFromStorageByIdForCurrentLocale($idAbstractProduct);
}
