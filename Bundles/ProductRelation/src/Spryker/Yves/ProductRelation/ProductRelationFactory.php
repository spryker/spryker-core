<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductRelation\DataProvider\RelatedProductsDataProvider;
use Spryker\Yves\ProductRelation\DataProvider\UpSellingDataProvider;
use Spryker\Yves\ProductRelation\Resolver\ProductRelationDataProviderResolver;
use Spryker\Yves\ProductRelation\Sorting\RelationSorter;

/**
 * @method \Spryker\Client\ProductRelation\ProductRelationClientInterface getClient()
 */
class ProductRelationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface
     */
    protected function createRelatedProductsDataProvider()
    {
        return new RelatedProductsDataProvider($this->getClient(), $this->createRelationSorter());
    }

    /**
     * @return \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface
     */
    protected function createUpSellingDataProvider()
    {
        return new UpSellingDataProvider($this->getClient(), $this->createRelationSorter());
    }

    /**
     * @return \Spryker\Yves\ProductRelation\Sorting\RelationSorterInterface
     */
    protected function createRelationSorter()
    {
        return new RelationSorter();
    }

    /**
     * @return \Spryker\Yves\ProductRelation\Resolver\ProductRelationDataProviderResolverInterface
     */
    public function createDataProviderResolver()
    {
        return new ProductRelationDataProviderResolver([
            $this->createRelatedProductsDataProvider(),
            $this->createUpSellingDataProvider(),
        ]);
    }
}
