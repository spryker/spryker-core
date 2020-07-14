<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Extractor;

use ArrayObject;

interface ShipmentOrderItemTemplateExtractorInterface
{
    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function getAdditionalData(ArrayObject $items): array;
}
