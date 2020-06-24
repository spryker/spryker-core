<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship;

use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Expander\ContentBannerByCmsPageUuidResourceRelationshipExpander;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Expander\ContentBannerByCmsPageUuidResourceRelationshipExpanderInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReader;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CmsPagesContentBannersResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader\ContentBannerReaderInterface
     */
    public function createContentBannerReader(): ContentBannerReaderInterface
    {
        return new ContentBannerReader(
            $this->getCmsStorageClient(),
            $this->getStoreClient(),
            $this->getContentBannerRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Expander\ContentBannerByCmsPageUuidResourceRelationshipExpanderInterface
     */
    public function createContentBannerByCmsPageUuidResourceRelationshipExpander(): ContentBannerByCmsPageUuidResourceRelationshipExpanderInterface
    {
        return new ContentBannerByCmsPageUuidResourceRelationshipExpander(
            $this->createContentBannerReader()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsPagesContentBannersResourceRelationshipDependencyProvider::CLIENT_CMS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientInterface
     */
    public function getStoreClient(): CmsPagesContentBannersResourceRelationshipToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsPagesContentBannersResourceRelationshipDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface
     */
    public function getContentBannerRestApiResource(): CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface
    {
        return $this->getProvidedDependency(CmsPagesContentBannersResourceRelationshipDependencyProvider::RESOURCE_CONTENT_BANNERS_REST_API);
    }
}
