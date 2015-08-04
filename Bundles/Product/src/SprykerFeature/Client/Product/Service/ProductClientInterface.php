<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Service;


interface ProductClientInterface
{
    /**
     * @param $idAbstractProduct
     * @param $locale
     * @return array
     */
    public function getAbstractProductFromStorageById($idAbstractProduct, $locale);
}
