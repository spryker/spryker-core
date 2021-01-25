<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentNavigationGui\ContentNavigationGuiConfig;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentNavigationGui\Communication\ContentNavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig getConfig()
 */
class ContentNavigationContentGuiEditorPlugin extends AbstractPlugin implements ContentGuiEditorPluginInterface
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
        return ContentNavigationGuiConfig::CONTENT_TYPE_NAVIGATION;
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
        return $this->getFactory()
            ->createContentNavigationContentGuiEditorConfigurationMapper()
            ->getTemplates();
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
        return $this->getFactory()
            ->createContentNavigationContentGuiEditorConfigurationMapper()
            ->getTwigFunctionTemplate();
    }
}
