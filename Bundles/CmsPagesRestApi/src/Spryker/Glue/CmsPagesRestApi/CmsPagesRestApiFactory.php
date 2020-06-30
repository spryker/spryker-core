<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi;

use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapper;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapperInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\Reader\CmsPageReader;
use Spryker\Glue\CmsPagesRestApi\Processor\Reader\CmsPageReaderInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilder;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\UrlResolver\CmsPageUrlResolver;
use Spryker\Glue\CmsPagesRestApi\Processor\UrlResolver\CmsPageUrlResolverInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CmsPagesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\Reader\CmsPageReaderInterface
     */
    public function createCmsPageReader(): CmsPageReaderInterface
    {
        return new CmsPageReader(
            $this->createCmsPageRestResponseBuilder(),
            $this->getCmsStorageClient(),
            $this->getCmsPageSearchClient(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface
     */
    public function createCmsPageRestResponseBuilder(): CmsPageRestResponseBuilderInterface
    {
        return new CmsPageRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCmsPageMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPageMapperInterface
     */
    public function createCmsPageMapper(): CmsPageMapperInterface
    {
        return new CmsPageMapper();
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsPagesRestApiToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsPagesRestApiDependencyProvider::CLIENT_CMS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface
     */
    public function getCmsPageSearchClient(): CmsPagesRestApiToCmsPageSearchClientInterface
    {
        return $this->getProvidedDependency(CmsPagesRestApiDependencyProvider::CLIENT_CMS_PAGE_SEARCH);
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface
     */
    public function getStoreClient(): CmsPagesRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsPagesRestApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\UrlResolver\CmsPageUrlResolverInterface
     */
    public function createCmsPageUrlResolver(): CmsPageUrlResolverInterface
    {
        return new CmsPageUrlResolver(
            $this->getCmsStorageClient(),
            $this->getStoreClient()
        );
    }
}
