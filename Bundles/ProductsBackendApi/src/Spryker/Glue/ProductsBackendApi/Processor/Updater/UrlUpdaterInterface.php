<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\UrlCollectionTransfer;

interface UrlUpdaterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsUrlsAttributesTransfer> $apiProductsUrlsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnCreate(ArrayObject $apiProductsUrlsAttributesTransfers): UrlCollectionTransfer;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsUrlsAttributesTransfer> $apiProductsUrlsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnUpdate(int $idProductAbstract, ArrayObject $apiProductsUrlsAttributesTransfers): UrlCollectionTransfer;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsUrlsAttributesTransfer> $apiProductsUrlsAttributesTransfers
     *
     * @return void
     */
    public function createUrls(int $idProductAbstract, ArrayObject $apiProductsUrlsAttributesTransfers): void;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsUrlsAttributesTransfer> $apiProductsUrlsAttributesTransfers
     *
     * @return void
     */
    public function updateUrls(int $idProductAbstract, ArrayObject $apiProductsUrlsAttributesTransfers): void;
}
