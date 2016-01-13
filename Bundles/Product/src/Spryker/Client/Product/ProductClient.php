<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method ProductFactory getFactory()
 */
class ProductClient extends AbstractClient implements ProductClientInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract)
    {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $productStorage = $this->getFactory()->createProductStorage($locale);
        $product = $productStorage->getProductAbstractFromStorageById($idProductAbstract);

        return $product;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale)
    {
        $productStorage = $this->getFactory()->createProductStorage($locale);
        $product = $productStorage->getProductAbstractFromStorageById($idProductAbstract);

        return $product;
    }

}
