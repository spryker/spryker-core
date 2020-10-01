<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource\ProductBundlesRestApiToOrdersRestApiResourceInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Expander\BundledProductExpander;
use Spryker\Glue\ProductBundlesRestApi\Processor\Expander\BundledProductExpanderInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Mapper\OrderMapper;
use Spryker\Glue\ProductBundlesRestApi\Processor\Mapper\OrderMapperInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReader;
use Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilder;
use Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig getConfig()
 */
class ProductBundlesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Processor\Expander\BundledProductExpanderInterface
     */
    public function createBundledProductExpander(): BundledProductExpanderInterface
    {
        return new BundledProductExpander($this->createBundledProductReader());
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface
     */
    public function createBundledProductReader(): BundledProductReaderInterface
    {
        return new BundledProductReader(
            $this->getProductStorageClient(),
            $this->getProductBundleStorageClient(),
            $this->createBundledProductRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Processor\Mapper\OrderMapperInterface
     */
    public function createOrderMapper(): OrderMapperInterface
    {
        return new OrderMapper($this->getOrdersRestApiResource());
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface
     */
    public function createBundledProductRestResponseBuilder(): BundledProductRestResponseBuilderInterface
    {
        return new BundledProductRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductBundlesRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface
     */
    public function getProductBundleStorageClient(): ProductBundlesRestApiToProductBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::CLIENT_PRODUCT_BUNDLE_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource\ProductBundlesRestApiToOrdersRestApiResourceInterface
     */
    public function getOrdersRestApiResource(): ProductBundlesRestApiToOrdersRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::RESOURCE_ORDERS_REST_API);
    }
}
