<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProduct;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\PyzProduct\Mapper\AttributeVariantMapper;
use Spryker\Yves\PyzProduct\Mapper\StorageProductMapper;
use Spryker\Yves\PyzProduct\ResourceCreator\ProductResourceCreator;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 */
class PyzProductFactory extends AbstractFactory
{

    /**
     * @return ResourceCreator\ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return new ProductResourceCreator();
    }

    /**
     * @return \Spryker\Yves\PyzProduct\Mapper\StorageProductMapperInterface
     */
    public function createStorageProductMapper()
    {
        return new StorageProductMapper($this->createAttributeVariantMapper(), $this->getStorageProductExpanderPlugins());
    }

    /**
     * @return \Spryker\Yves\PyzProduct\Mapper\AttributeVariantMapperInterface
     */
    public function createAttributeVariantMapper()
    {
        return new AttributeVariantMapper($this->getClient());
    }

    /**
     * @return \Spryker\Client\ProductGroup\ProductGroupClientInterface
     */
    public function getProductGroupClient()
    {
        return $this->getProvidedDependency(PyzProductDependencyProvider::CLIENT_PRODUCT_GROUP);
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\ControllerResponseExpanderPluginInterface[]
     */
    public function getControllerResponseExtenderPlugins()
    {
        return $this->getProvidedDependency(PyzProductDependencyProvider::PLUGIN_CONTROLLER_RESPONSE_EXPANDERS);
    }

    /**
     * @return \Spryker\Yves\PyzProduct\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected function getStorageProductExpanderPlugins()
    {
        return $this->getProvidedDependency(PyzProductDependencyProvider::PLUGIN_STORAGE_PRODUCT_EXPANDERS);
    }

}
