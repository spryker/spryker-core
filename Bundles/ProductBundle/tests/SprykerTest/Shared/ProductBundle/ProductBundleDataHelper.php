<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductBundle;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductForBundleBuilder;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductBundleDataHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle
     */
    public function createProductBundle(array $override): SpyProductBundle
    {
        $productForBundleTransfer = (new ProductForBundleBuilder())->seed($override)->build();
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setFkProduct($productForBundleTransfer->getIdProductConcrete());
        $productBundleEntity->setFkBundledProduct($productForBundleTransfer->getIdProductBundle());
        $productBundleEntity->setQuantity($productForBundleTransfer->getQuantity());

        $productBundleEntity->save();

        return $productBundleEntity;
    }
}
