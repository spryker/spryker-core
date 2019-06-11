<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication;

use Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint;
use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapper;
use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapperInterface;
use Spryker\Zed\ContentBannerGui\ContentBannerGuiDependencyProvider;
use Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerFacadeInterface;
use Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig getConfig()
 */
class ContentBannerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array $options
     *
     * @return \Spryker\Zed\ContentBannerGui\Communication\Form\Constraints\ContentBannerConstraint
     */
    public function createContentBannerConstraint(array $options = []): ContentBannerConstraint
    {
        return new ContentBannerConstraint(
            $this->getContentBannerFacade(),
            $this->getUtilEncoding(),
            $options
        );
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapperInterface
     */
    public function createContentBannerContentGuiEditorMapper(): ContentBannerContentGuiEditorConfigurationMapperInterface
    {
        return new ContentBannerContentGuiEditorConfigurationMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerFacadeInterface
     */
    public function getContentBannerFacade(): ContentBannerGuiToContentBannerFacadeInterface
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
