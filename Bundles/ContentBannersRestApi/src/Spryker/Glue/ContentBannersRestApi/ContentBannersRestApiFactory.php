<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi;

use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface;
use Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapper;
use Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\ContentBannerReader;
use Spryker\Glue\ContentBannersRestApi\Processor\ContentBannerReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentBannersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Processor\ContentBannerReaderInterface
     */
    public function createContentBannerReader(): ContentBannerReaderInterface
    {
        return new ContentBannerReader(
            $this->getResourceBuilder(),
            $this->createContentBannerMapper(),
            $this->getContentBannerClient()
        );
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface
     */
    public function createContentBannerMapper(): ContentBannerMapperInterface
    {
        return new ContentBannerMapper();
    }

    /**
     * @return \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface
     */
    public function getContentBannerClient(): ContentBannersRestApiToContentBannerClientInterface
    {
        return $this->getProvidedDependency(ContentBannersRestApiDependencyProvider::CLIENT_CONTENT_BANNER);
    }
}
