<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi;

use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\CmsPage\CmsPageReader;
use Spryker\Glue\CmsPagesRestApi\Processor\CmsPage\CmsPageReaderInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapper;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapperInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilder;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CmsPagesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\CmsPage\CmsPageReaderInterface
     */
    public function createCmsPageReader(): CmsPageReaderInterface
    {
        return new CmsPageReader(
            $this->createCmsPageRestResponseBuilder(),
            $this->getCmsStorageClient(),
            $this->getCmsPageSearchClient(),
            $this->createCmsPagesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface
     */
    public function createCmsPageRestResponseBuilder(): CmsPageRestResponseBuilderInterface
    {
        return new CmsPageRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapperInterface
     */
    public function createCmsPagesResourceMapper(): CmsPagesResourceMapperInterface
    {
        return new CmsPagesResourceMapper();
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
        return $this->getProvidedDependency(CmsPagesRestApiDependencyProvider::CLIENT_CMS_SEARCH_PAGE);
    }
}
