<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi;

use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientInterface;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\Expander\ContentBannersByCmsPageReferenceResourceRelationshipExpander;
use Spryker\Glue\ContentBannersRestApi\Processor\Expander\ContentBannersByCmsPageReferenceResourceRelationshipExpanderInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\Mapper\ContentBannerMapper;
use Spryker\Glue\ContentBannersRestApi\Processor\Mapper\ContentBannerMapperInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\Reader\ContentBannerReader;
use Spryker\Glue\ContentBannersRestApi\Processor\Reader\ContentBannerReaderInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilder;
use Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentBannersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Processor\Reader\ContentBannerReaderInterface
     */
    public function createContentBannerReader(): ContentBannerReaderInterface
    {
        return new ContentBannerReader(
            $this->getContentBannerClient(),
            $this->createContentBannerRestResponseBuilder(),
            $this->getCmsStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Processor\Mapper\ContentBannerMapperInterface
     */
    public function createContentBannerMapper(): ContentBannerMapperInterface
    {
        return new ContentBannerMapper();
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilderInterface
     */
    public function createContentBannerRestResponseBuilder(): ContentBannerRestResponseBuilderInterface
    {
        return new ContentBannerRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createContentBannerMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Processor\Expander\ContentBannersByCmsPageReferenceResourceRelationshipExpanderInterface
     */
    public function createContentBannersByCmsPageReferenceResourceRelationshipExpander(): ContentBannersByCmsPageReferenceResourceRelationshipExpanderInterface
    {
        return new ContentBannersByCmsPageReferenceResourceRelationshipExpander(
            $this->createContentBannerReader()
        );
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface
     */
    public function getContentBannerClient(): ContentBannersRestApiToContentBannerClientInterface
    {
        return $this->getProvidedDependency(ContentBannersRestApiDependencyProvider::CLIENT_CONTENT_BANNER);
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): ContentBannersRestApiToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(ContentBannersRestApiDependencyProvider::CLIENT_CMS_STORAGE);
    }
}
