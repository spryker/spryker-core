<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

interface ProductClientInterface
{

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getAbstractProductFromStorageById($idProductAbstract, $locale);

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAbstractProductFromStorageByIdForCurrentLocale($idProductAbstract);

}
