<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Allows to replace the template, that renders item table, for return create page.
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
     * @return mixed[]
     */
    public function getTemplateData(OrderTransfer $orderTransfer): array;
}
