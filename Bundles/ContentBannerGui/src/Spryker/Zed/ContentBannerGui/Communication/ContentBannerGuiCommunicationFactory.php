<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication;

use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorMapper;
use Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorMapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class ContentBannerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui\ContentBannerContentGuiEditorMapperInterface
     */
    public function createContentBannerContentGuiEditorMapper(): ContentBannerContentGuiEditorMapperInterface
    {
        return new ContentBannerContentGuiEditorMapper();
    }
}
