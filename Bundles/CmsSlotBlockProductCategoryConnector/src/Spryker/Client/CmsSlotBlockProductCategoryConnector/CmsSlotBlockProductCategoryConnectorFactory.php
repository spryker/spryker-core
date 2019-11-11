<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector;

use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReader;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver\ProductCategoryCmsSlotBlockConditionResolver;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver\ProductCategoryCmsSlotBlockConditionResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotBlockProductCategoryConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver\ProductCategoryCmsSlotBlockConditionResolverInterface
     */
    public function createProductCategoryCmsSlotBlockConditionResolver(): ProductCategoryCmsSlotBlockConditionResolverInterface
    {
        return new ProductCategoryCmsSlotBlockConditionResolver(
            $this->createProductCategoryReader()
        );
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface
     */
    public function createProductCategoryReader(): ProductCategoryReaderInterface
    {
        return new ProductCategoryReader(
            $this->getLocaleClient(),
            $this->getProductCategoryStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface
     */
    public function getLocaleClient(): CmsSlotBlockProductCategoryConnectorToLocaleClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface
     */
    public function getProductCategoryStorageClient(): CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_PRODUCT_CATEGORY_STORAGE);
    }
}
