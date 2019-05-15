<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentBannerGui\Communication\ContentBannerGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig getConfig()
 */
class ContentBannerContentGuiEditorPlugin extends AbstractPlugin implements ContentGuiEditorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return ContentBannerGuiConfig::CONTENT_TYPE_BANNER;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        return $this->getFactory()->createContentBannerContentGuiEditorMapper()->getTemplates();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTwigFunctionTemplate(): string
    {
        return $this->getFactory()->createContentBannerContentGuiEditorMapper()->getTwigFunctionTemplate();
    }
}
