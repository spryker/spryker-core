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
     * @param \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[] $restProductOptionAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[]
     */
    public function sortRestProductOptionAttributesTransfers(
        array $restProductOptionAttributesTransfers,
        RestRequestInterface $restRequest
    ): array;
}
