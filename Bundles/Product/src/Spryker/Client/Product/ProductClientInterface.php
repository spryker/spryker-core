<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product;

interface ProductClientInterface
{
    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array
     */
    public function getAttributeMapByIdAndLocale($idProductAbstract, $locale);

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function getProductConcreteByIdForCurrentLocale($idProductConcrete);

    /**
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

    /**
     * Specification:
     * - Maps raw product data to StorageProductTransfer for the given locale.
     * - Based on the super attributes and the selected attributes of the product the result might be abstract or concrete product.
     * - Executes a stack of \Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface plugins that
     * can expand the result with extra data.
     *
     * @api
     *
     * @param string $locale
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct($locale, array $data, array $selectedAttributes = []);

    /**
     * Specification:
     * - Maps raw product data to StorageProductTransfer for the current locale.
     * - Based on the super attributes and the selected attributes of the product the result might be abstract or concrete product.
     * - Executes a stack of \Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface plugins that
     * can expand the result with extra data.
     *
     * @api
     *
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProductForCurrentLocale(array $data, array $selectedAttributes = []);
}
