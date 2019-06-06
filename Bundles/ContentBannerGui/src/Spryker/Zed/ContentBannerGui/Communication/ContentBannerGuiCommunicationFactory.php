<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication;

use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapper;
use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig getConfig()
 */
class ContentBannerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorConfigurationMapperInterface
     */
    public function createContentBannerContentGuiEditorMapper(): ContentBannerContentGuiEditorConfigurationMapperInterface
    {
        return new ContentBannerContentGuiEditorConfigurationMapper($this->getConfig());
    }
}
