<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentProductSetGui\ContentProductSetGuiConfig;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentProductSetGui\Communication\ContentProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig getConfig()
 */
class ContentProductSetGuiEditorPlugin extends AbstractPlugin implements ContentGuiEditorPluginInterface
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
        return ContentProductSetGuiConfig::CONTENT_TYPE_PRODUCT_SET;
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
        return $this->getFactory()->createContentProductSetGuiEditorMapper()->getTemplates();
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
        return $this->getFactory()->createContentProductSetGuiEditorMapper()->getTwigFunctionTemplate();
    }
}
