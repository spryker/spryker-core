<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\ProductBundle\Persistence\Propel;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle as BaseSpyProductBundle;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\ProductBundle\Persistence\Exception\BundleConnectionViolationException;

/**
 * Skeleton subclass for representing a row from the 'spy_product_bundle' table.
 *
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyProductBundle extends BaseSpyProductBundle
{
    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @throws \Spryker\Zed\ProductBundle\Persistence\Exception\BundleConnectionViolationException
     *
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        //Do no accept connections to already bundled concretes or concretes having bundled items.
        $numberOfBundlesUsing = SpyProductBundleQuery::create()
            ->filterByFkProduct($this->fk_bundled_product)
            ->_or()
            ->filterByFkBundledProduct($this->fk_product)
            ->count();

        if ($numberOfBundlesUsing > 0) {
            throw new BundleConnectionViolationException('Cannot assign bundle product or use bundled product as a bundle.');
        }

        return true;
    }

    /**
     * @deprecated Can be removed with the next major.
     * BC method; PropelRM ObjectBuilder is updated and uses phpName for generating method names. We added phpName="BundledProduct"
     * to the foreignKey definition and by that this method no longer exists. To prevent projects form breaking up when using this
     * (no longer generated) method this one is added.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con Optional Connection object.
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct The associated SpyProduct object.
     */
    public function getSpyProductRelatedByFkBundledProduct(?ConnectionInterface $con = null): SpyProduct
    {
        if (method_exists(BaseSpyProductBundle::class, 'getSpyProductRelatedByFkBundledProduct')) {
            /** @phpstan-ignore-next-line */
            return parent::getSpyProductRelatedByFkBundledProduct($con);
        }

        return $this->getBundledProduct($con);
    }

    /**
     * @deprecated Can be removed with the next major.
     *
     * BC method; PropelRM ObjectBuilder is updated and uses phpName for generating method names. We added phpName="BundledProduct"
     * to the foreignKey definition and by that this method no longer exists. To prevent projects form breaking up when using this
     * (no longer generated) method this one is added.
     *
     * Declares an association between this object and a SpyProduct object.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProduct|null $v
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle The current object (for fluent API support)
     */
    public function setSpyProductRelatedByFkBundledProduct(?SpyProduct $v = null): SpyProductBundle
    {
        if (method_exists(BaseSpyProductBundle::class, 'setSpyProductRelatedByFkBundledProduct')) {
            /** @phpstan-ignore-next-line */
            return parent::setSpyProductRelatedByFkBundledProduct($v);
        }

        /** @phpstan-var \Orm\Zed\ProductBundle\Persistence\SpyProductBundle */
        return $this->setBundledProduct($v);
    }
}
