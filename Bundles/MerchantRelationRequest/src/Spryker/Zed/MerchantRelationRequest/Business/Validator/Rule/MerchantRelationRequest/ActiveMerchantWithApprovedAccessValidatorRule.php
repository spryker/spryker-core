<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;

class ActiveMerchantWithApprovedAccessValidatorRule implements MerchantRelationValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_NOT_FOUND = 'merchant_relation_request.validation.merchant_not_found';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReaderInterface $merchantReader
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantReaderInterface $merchantReader
    ) {
        $this->errorAdder = $errorAdder;
        $this->merchantReader = $merchantReader;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantRelationRequestTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $existingMerchants = $this->merchantReader->getMerchantsIndexedByIdMerchant(
            $this->extractMerchantIds($merchantRelationRequestTransfers),
        );

        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            $idMerchant = $merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail();
            $merchantTransfer = $existingMerchants[$idMerchant] ?? null;

            if (!$merchantTransfer) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_MERCHANT_NOT_FOUND,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return list<int>
     */
    protected function extractMerchantIds(ArrayObject $merchantRelationRequestTransfers): array
    {
        $merchantIds = [];
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $merchantIds[] = $merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail();
        }

        return array_unique($merchantIds);
    }
}
