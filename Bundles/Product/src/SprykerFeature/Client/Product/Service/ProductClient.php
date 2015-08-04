<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method ProductDependencyContainer getDependencyContainer()
 */
class ProductClient extends AbstractClient implements ProductClientInterface
{

    /**
     * @param $idAbstractProduct
     * @param $locale (e.g. 'de_DE')
     * @return array
     */
    public function getAbstractProductFromStorageById($idAbstractProduct, $locale)
    {
        $productStorage = $this->getDependencyContainer()->createProductStorage($locale);
        $product = $productStorage->getAbstractProductFromStorageById($idAbstractProduct);
        return $product;
    }
}
