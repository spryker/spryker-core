<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
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
            $this->getContentProductClient(),
            $this->getProductRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    public function getContentProductClient(): ContentProductAbstractListsRestApiToContentProductClientInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::CLIENT_CONTENT_PRODUCT);
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    public function getProductRestApiResource(): ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::RESOURCE_PRODUCTS_REST_API);
    }
}
