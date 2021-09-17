<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Sorter;

interface ProductOptionSorterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\RestProductOptionsAttributesTransfer> $restProductOptionsAttributesTransfers
     * @param array<\Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface> $sorts
     *
     * @return array<\Generated\Shared\Transfer\RestProductOptionsAttributesTransfer>
     */
    public function sortRestProductOptionsAttributesTransfers(
        array $restProductOptionsAttributesTransfers,
        array $sorts
    ): array;
}
