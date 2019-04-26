<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication;

use Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint;
use Spryker\Zed\ContentBannerGui\ContentBannerGuiDependencyProvider;
use Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface;
use Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class ContentBannerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint
     */
    public function createContentBannerConstraint(): ContentBannerConstraint
    {
        return new ContentBannerConstraint(
            $this->getContentBannerFacade(),
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface
     */
    public function getContentBannerFacade(): ContentBannerGuiToContentBannerInterface
    {
        return $this->getProvidedDependency(ContentBannerGuiDependencyProvider::FACADE_CONTENT_BANNER);
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface
     */
    public function getUtilEncoding(): ContentBannerGuiToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ContentBannerGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
