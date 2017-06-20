<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttributeGui\Business\Model\AttributeLoader;
use Spryker\Zed\ProductAttributeGui\Business\Model\ProductAttributeManager;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Business\Model\ProductAttributeManagerInterface
     */
    public function createProductAttributeManager()
    {
        return new ProductAttributeManager(
            $this->getProductQueryContainer(),
            $this->createAttributeLoader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeLoaderInterface
     */
    public function createAttributeLoader()
    {
        return new AttributeLoader(
            $this->getProductManagementQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected function getProductManagementQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_MANAGEMENT);
    }

}
