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
     * - Returns type of content item.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Specification:
     * - Returns ContentWidgetTemplateTransfer with template options of content item.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array;
}
