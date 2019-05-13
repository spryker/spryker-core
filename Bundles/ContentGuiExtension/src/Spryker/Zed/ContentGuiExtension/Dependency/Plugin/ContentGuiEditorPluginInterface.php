<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGuiExtension\Dependency\Plugin;

interface ContentGuiEditorPluginInterface
{
    /**
     * Specification:
     * - Returns content type.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Specification:
     * - Returns ContentWidgetTemplateTransfers with options of content type templates.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array;

    /**
     * Specification:
     * - Returns twig function template of content item type.
     *
     * @api
     *
     * @return string
     */
    public function getTwigFunctionTemplate(): string;
}
