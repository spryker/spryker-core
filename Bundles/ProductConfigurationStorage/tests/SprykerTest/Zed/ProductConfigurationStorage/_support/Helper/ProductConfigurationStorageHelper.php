<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationStorage\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductConfigurationStorageBuilder;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage;
use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorageQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductConfigurationStorageHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function haveProductConfigurationStorage(array $seedData = []): ProductConfigurationStorageTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer */
        $productConfigurationStorageTransfer = (new ProductConfigurationStorageBuilder($seedData))->build();

        $productConfigurationStorageEntity = new SpyProductConfigurationStorage();
        $productConfigurationStorageEntity->fromArray($productConfigurationStorageTransfer->toArray());
        $productConfigurationStorageEntity->save();

        $productConfigurationStorageTransfer->setIdProductConfigurationStorage(
            $productConfigurationStorageEntity->getIdProductConfigurationStorage()
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConfigurationStorageTransfer) {
            $this->removeProductConfigurationStorage($productConfigurationStorageTransfer);
        });

        return $productConfigurationStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return void
     */
    protected function removeProductConfigurationStorage(ProductConfigurationStorageTransfer $productConfigurationStorageTransfer): void
    {
        $this->getProductConfigurationStorageQuery()->filterByIdProductConfigurationStorage(
            $productConfigurationStorageTransfer->getIdProductConfigurationStorage()
        )->delete();
    }

    /**
     * @return \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorageQuery
     */
    protected function getProductConfigurationStorageQuery(): SpyProductConfigurationStorageQuery
    {
        return SpyProductConfigurationStorageQuery::create();
    }
}
