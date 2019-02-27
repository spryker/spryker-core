<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi;

use Spryker\Glue\ContentProductsRestApi\Dependency\Client\ContentProductsRestApiToContentStorageClientInterface;
use Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapper;
use Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapperInterface;
use Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReader;
use Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReaderInterface
     */
    public function createContentProductReader(): ContentProductReaderInterface
    {
        return new ContentProductReader(
            $this->getResourceBuilder(),
            $this->getContentStorageClient(),
            $this->createContentAbstractProductMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapperInterface
     */
    public function createContentAbstractProductMapper(): ContentAbstractProductMapperInterface
    {
        return new ContentAbstractProductMapper();
    }

    /**
     * @return \Spryker\Glue\ContentProductsRestApi\Dependency\Client\ContentProductsRestApiToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductsRestApiToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductsRestApiDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
