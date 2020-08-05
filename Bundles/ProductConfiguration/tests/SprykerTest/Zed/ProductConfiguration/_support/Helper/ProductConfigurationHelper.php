<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductConfigurationBuilder;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfiguration;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductConfigurationHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer
     */
    public function haveProductConfiguration(array $seedData = []): ProductConfigurationTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer */
        $productConfigurationTransfer = (new ProductConfigurationBuilder($seedData))->build();

        $productConfigurationEntity = new SpyProductConfiguration();
        $productConfigurationEntity->fromArray($productConfigurationTransfer->toArray());
        $productConfigurationEntity->save();

        $productConfigurationTransfer->setIdProductConfiguration($productConfigurationEntity->getIdProductConfiguration());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConfigurationTransfer) {
            $this->getProductConfigurationQuery()->filterByIdProductConfiguration(
                $productConfigurationTransfer->getIdProductConfiguration()
            )->delete();
        });

        return $productConfigurationTransfer;
    }

    /**
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function getProductConfigurationQuery(): SpyProductConfigurationQuery
    {
        return SpyProductConfigurationQuery::create();
    }
}
