<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

abstract class AbstractStatusApplicableRequestValidatorRule implements MerchantRelationValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface
     */
    protected MerchantRelationRequestReaderInterface $merchantRelationRequestReader;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    abstract protected function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool;

    /**
     * @param string|int $entityIdentifier
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param array<\Generated\Shared\Transfer\MerchantRelationRequestTransfer> $existingMerchantRelationRequests
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return void
     */
    abstract protected function validateRequest(
        int|string $entityIdentifier,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $existingMerchantRelationRequests,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): void;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantRelationRequestTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $existingMerchantRelationRequests = $this->merchantRelationRequestReader->getMerchantRelationRequestsIndexedByUuid(
            $this->extractApplicableMerchantRelationRequestUuids($merchantRelationRequestTransfers),
        );

        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            if (!$this->isApplicable($merchantRelationRequestTransfer)) {
                continue;
            }

            $this->validateRequest(
                $entityIdentifier,
                $merchantRelationRequestTransfer,
                $existingMerchantRelationRequests,
                $errorCollectionTransfer,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return list<string>
     */
    protected function extractApplicableMerchantRelationRequestUuids(ArrayObject $merchantRelationRequestTransfers): array
    {
        $merchantRelationRequestUuids = [];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            if ($this->isApplicable($merchantRelationRequestTransfer)) {
                $merchantRelationRequestUuids[] = $merchantRelationRequestTransfer->getUuidOrFail();
            }
        }

        return $merchantRelationRequestUuids;
    }
}
