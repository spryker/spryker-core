<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PyzProduct;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Product\ProductClientInterface;

/**
 * @method \Spryker\Client\PyzProduct\PyzProductFactory getFactory()
 * TODO: this should be a bridge
 */
class PyzProductClient extends AbstractClient implements ProductClientInterface
{

    /**
     * Specification:
     * - Read abstract product data from yves storage, based on current shop selected locale
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract)
    {
        return $this->getFactory()->getProductClient()->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);
    }

    /**
     * Specification:
     * - Read abstract product data from yves storage, based on provided
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale)
    {
        return $this->getFactory()->getProductClient()->getProductAbstractFromStorageById($idProductAbstract, $locale);
    }

    /**
     * Specification:
     * - Read concrete product data from yves storage, based on current shop selected locale
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function getProductConcreteByIdForCurrentLocale($idProductConcrete)
    {
        return $this->getFactory()->getProductClient()->getProductConcreteByIdForCurrentLocale($idProductConcrete);
    }

    /**
     * Specification:
     * - Read concrete product data from yves storage, based on provided locale
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return array
     */
    public function getProductConcreteByIdAndLocale($idProductConcrete, $locale)
    {
        return $this->getFactory()->getProductClient()->getProductConcreteByIdAndLocale($idProductConcrete, $locale);
    }

    /**
     * Specification:
     * - Read attribute map from storage, based on current shop selected locale
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract)
    {
        return $this->getFactory()->getProductClient()->getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract);
    }

    /**
     * Specification:
     * - Read attribute map from storage, based on provided
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getAttributeMapByIdAndLocale($idProductAbstract, $locale)
    {
        return $this->getFactory()->getProductClient()->getAttributeMapByIdAndLocale($idProductAbstract, $locale);
    }

    /**
     * Specification:
     * - Read product concrete information based on product concrete id collection
     *
     * @api
     *
     * @param array $idProductConcreteCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function getProductConcreteCollection(array $idProductConcreteCollection)
    {
        return $this->getFactory()->getProductClient()->getProductConcreteCollection($idProductConcreteCollection);
    }

}
