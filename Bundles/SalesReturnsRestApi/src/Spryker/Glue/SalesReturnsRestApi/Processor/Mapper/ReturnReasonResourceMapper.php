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
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer[]
     */
    public function mapReturnReasonTransfersToRestReturnReasonsAttributesTransfers(
        ArrayObject $returnReasonTransfers,
        string $localeName
    ): array {
        $restReturnReasonsAttributesTransfers = [];
        $translatedReturnReasons = $this->translateReturnReasons($returnReasonTransfers, $localeName);

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $restReturnReasonsAttributesTransfer = (new RestReturnReasonsAttributesTransfer())
                ->fromArray($returnReasonTransfer->toArray(), true);

            $restReturnReasonsAttributesTransfer->setReason(
                $translatedReturnReasons[$returnReasonTransfer->getGlossaryKeyReason()] ?? null
            );

            $restReturnReasonsAttributesTransfers[] = $restReturnReasonsAttributesTransfer;
        }

        return $restReturnReasonsAttributesTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnReasonTransfer[] $returnReasonTransfers
     * @param string $localeName
     *
     * @return string[]
     */
    protected function translateReturnReasons(ArrayObject $returnReasonTransfers, string $localeName): array
    {
        $glossaryKeyReasons = [];

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $glossaryKeyReasons[] = $returnReasonTransfer->getGlossaryKeyReason();
        }

        return $this->glossaryStorageClient->translateBulk($glossaryKeyReasons, $localeName);
    }
}
