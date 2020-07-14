<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin;

use ArrayObject;

interface ShipmentOrderItemTemplatePluginInterface
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
     *  - Returns addition data for template.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return array
     */
    public function getAdditionData(ArrayObject $itemTransfers): array;
}
