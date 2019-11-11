<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Sorter;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ProductOptionSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[] $restProductOptionsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[]
     */
    public function sortRestProductOptionsAttributesTransfers(
        array $restProductOptionsAttributesTransfers,
        RestRequestInterface $restRequest
    ): array;
}
