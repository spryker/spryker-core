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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnCreate(ArrayObject $productUrlsBackendApiAttributesTransfers): UrlCollectionTransfer;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnUpdate(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): UrlCollectionTransfer;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return void
     */
    public function createUrls(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): void;

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return void
     */
    public function updateUrls(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): void;
}
