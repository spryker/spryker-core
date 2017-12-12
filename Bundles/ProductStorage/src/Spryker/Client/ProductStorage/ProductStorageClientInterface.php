<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

interface ProductStorageClientInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return ProductAbstractStorageTransfer
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale);

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
