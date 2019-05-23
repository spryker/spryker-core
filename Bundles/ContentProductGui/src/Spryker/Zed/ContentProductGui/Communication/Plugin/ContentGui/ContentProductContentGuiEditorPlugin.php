<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentProductGui\ContentProductGuiConfig;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentProductGui\Communication\ContentProductGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductGui\ContentProductGuiConfig getConfig()
 */
class ContentProductContentGuiEditorPlugin extends AbstractPlugin implements ContentGuiEditorPluginInterface
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
        return ContentProductGuiConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST;
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
        return $this->getFactory()->createContentProductContentGuiEditorMapper()->getTemplates();
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
        return $this->getFactory()->createContentProductContentGuiEditorMapper()->getTwigFunctionTemplate();
    }
}
