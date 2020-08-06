<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Mapper;

use ArrayObject;

interface ReturnReasonResourceMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnReasonSearchTransfer[] $returnReasonSearchTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer[]
     */
    public function mapReturnReasonSearchTransfersToRestReturnReasonsAttributesTransfers(
        ArrayObject $returnReasonSearchTransfers,
        string $localeName
    ): array;
}
