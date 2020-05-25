<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer;

class ReturnReasonResourceMapper implements ReturnReasonResourceMapperInterface
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
    ): array {
        $restReturnReasonsAttributesTransfers = [];

        foreach ($returnReasonSearchTransfers as $returnReasonSearchTransfer) {
            $restReturnReasonsAttributesTransfer = (new RestReturnReasonsAttributesTransfer())
                ->fromArray($returnReasonSearchTransfer->toArray(), true);

            $restReturnReasonsAttributesTransfer->setReason(
                $returnReasonSearchTransfer->getName()
            );

            $restReturnReasonsAttributesTransfers[] = $restReturnReasonsAttributesTransfer;
        }

        return $restReturnReasonsAttributesTransfers;
    }
}
