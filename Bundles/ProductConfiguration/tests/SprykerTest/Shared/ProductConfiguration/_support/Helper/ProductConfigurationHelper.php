<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductConfiguration\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductConfigurationBuilder;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class ProductConfigurationHelper extends Module
{
    use BusinessHelperTrait;
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    public function haveProductConfigurationTransfer(array $seed = []): ProductConfigurationTransfer
    {
        if (!isset($seed[ProductConfigurationTransfer::FK_PRODUCT])) {
            $productConcreteTransfer = $this->getProductDataHelper()->haveProduct();
            $seed = array_merge(
                [
                    ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                ],
                $seed,
            );
        }

        return (new ProductConfigurationBuilder($seed))->build();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer|null
     */
    public function haveProductConfigurationTransferPersisted(array $seed = []): ?ProductConfigurationTransfer
    {
        return $this->persistProductConfiguration($this->haveProductConfigurationTransfer($seed));
    }

    /**
     * @param int $numberOfProductConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function haveProductConfigurationCollectionTransferWithProductConfigurationTransfersPersisted(
        int $numberOfProductConfigurationTransfers = 15
    ): ProductConfigurationCollectionTransfer {
        $productConfigurationCollectionTransfer = new ProductConfigurationCollectionTransfer();

        for ($i = 1; $i <= $numberOfProductConfigurationTransfers; $i++) {
            $productConfigurationTransfer = $this->haveProductConfigurationTransferPersisted();

            $productConfigurationCollectionTransfer->addProductConfiguration($productConfigurationTransfer);
        }

        return $productConfigurationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    protected function persistProductConfiguration(ProductConfigurationTransfer $productConfigurationTransfer): ProductConfigurationTransfer
    {
        $productConfigurationEntity = (new SpyProductConfiguration())->fromArray($productConfigurationTransfer->toArray());

        $productConfigurationEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConfigurationEntity): void {
            $this->deleteProductConfiguration($productConfigurationEntity->getIdProductConfiguration());
        });

        return $productConfigurationTransfer->fromArray($productConfigurationEntity->toArray(), true);
    }

    /**
     * @param int $idProductConfiguration
     *
     * @return void
     */
    protected function deleteProductConfiguration(int $idProductConfiguration): void
    {
        $this->debug(sprintf('Deleting product configuration: %d', $idProductConfiguration));

        $productConfigurationEntity = SpyProductConfigurationQuery::create()
            ->findOneByIdProductConfiguration($idProductConfiguration);

        if ($productConfigurationEntity) {
            $productConfigurationEntity->delete();
        }
    }

    /**
     * @return \SprykerTest\Shared\Product\Helper\ProductDataHelper
     */
    protected function getProductDataHelper(): ProductDataHelper
    {
        /** @var \SprykerTest\Shared\Product\Helper\ProductDataHelper $productDataHelper */
        $productDataHelper = $this->getModule('\\' . ProductDataHelper::class);

        return $productDataHelper;
    }
}
