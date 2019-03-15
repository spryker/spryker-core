<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentStorageClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\ContentProductAbstractListReader;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\ContentProductAbstractListReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentProductAbstractListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\ContentProductAbstractListReaderInterface
     */
    public function createContentProductAbstractListReader(): ContentProductAbstractListReaderInterface
    {
        return new ContentProductAbstractListReader(
            $this->getResourceBuilder(),
            $this->getContentStorageClient(),
            $this->getProductRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductAbstractListsRestApiToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::CLIENT_CONTENT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    public function getProductRestApiResource(): ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::RESOURCE_PRODUCTS_REST_API);
    }
}
