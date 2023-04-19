<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface} to get template data and handle subform.
 *
 * Allows to replace the template, that renders item table on return create page.
 */
interface ReturnCreateTemplatePluginInterface
{
    /**
     * Specification:
     *  - Returns template path.
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Specification:
     *  - Returns additional data for template.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<mixed>
     */
    public function getTemplateData(OrderTransfer $orderTransfer): array;
}
