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
    public function getAbstractProductFromStorageByIdForCurrentLocale($idProductAbstract)
    {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $productStorage = $this->getFactory()->createProductStorage($locale);
        $product = $productStorage->getAbstractProductFromStorageById($idProductAbstract);

        return $product;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getAbstractProductFromStorageById($idProductAbstract, $locale)
    {
        $productStorage = $this->getFactory()->createProductStorage($locale);
        $product = $productStorage->getAbstractProductFromStorageById($idProductAbstract);

        return $product;
    }

}
