<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product;

interface ProductClientInterface
{
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
    public function getProductAbstractFromStorageById($idProductAbstract, $locale);

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
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

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
    public function getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract);

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
    public function getAttributeMapByIdAndLocale($idProductAbstract, $locale);

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
    public function getProductConcreteByIdForCurrentLocale($idProductConcrete);

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
    public function getProductConcreteByIdAndLocale($idProductConcrete, $locale);

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
    public function getProductConcreteCollection(array $idProductConcreteCollection);
}
