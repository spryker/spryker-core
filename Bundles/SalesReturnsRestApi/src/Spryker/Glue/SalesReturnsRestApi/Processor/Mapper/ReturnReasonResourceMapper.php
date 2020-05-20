<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface;

class ReturnReasonResourceMapper implements ReturnReasonResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(SalesReturnsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[] $returnReasonPageSearchTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer[]
     */
    public function mapReturnReasonPageSearchTransfersToRestReturnReasonsAttributesTransfers(
        ArrayObject $returnReasonPageSearchTransfers,
        string $localeName
    ): array {
        $restReturnReasonsAttributesTransfers = [];

        foreach ($returnReasonPageSearchTransfers as $returnReasonPageSearchTransfer) {
            $restReturnReasonsAttributesTransfer = (new RestReturnReasonsAttributesTransfer())
                ->fromArray($returnReasonPageSearchTransfer->toArray(), true);

            $restReturnReasonsAttributesTransfer->setReason(
                $returnReasonPageSearchTransfer->getName()
            );

            $restReturnReasonsAttributesTransfers[] = $restReturnReasonsAttributesTransfer;
        }

        return $restReturnReasonsAttributesTransfers;
    }
}
