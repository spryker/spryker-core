<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

interface ProductClientInterface
{

    /**
     * @param int $idAbstractProduct
     * @param string $locale
     *
     * @return array
     */
    public function getAbstractProductFromStorageById($idAbstractProduct, $locale);

    /**
     * @param int $idAbstractProduct
     *
     * @return array
     */
    public function getAbstractProductFromStorageByIdForCurrentLocale($idAbstractProduct);

}
