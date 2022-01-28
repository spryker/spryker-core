<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\MerchantRelationshipErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface;

class MerchantRelationshipKeyUniqueValidatorRule implements MerchantRelationshipValidatorRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    protected $merchantRelationshipReader;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface $merchantRelationshipReader
     */
    public function __construct(MerchantRelationshipReaderInterface $merchantRelationshipReader)
    {
        $this->merchantRelationshipReader = $merchantRelationshipReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    public function validate(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        if (!$merchantRelationshipTransfer->getMerchantRelationshipKey()) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $existingMerchantRelationshipTransfer = $this->merchantRelationshipReader->findMerchantRelationshipByKey($merchantRelationshipTransfer);
        if (!$existingMerchantRelationshipTransfer) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
            MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY,
            sprintf('Merchant relationship key "%s" already exists.', $merchantRelationshipTransfer->getMerchantRelationshipKey()),
        );

        return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
    }

    /**
     * @param string $field
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer
     */
    protected function createMerchantRelationshipErrorTransfer(string $field, string $message): MerchantRelationshipErrorTransfer
    {
        return (new MerchantRelationshipErrorTransfer())
            ->setField($field)
            ->setMessage($message);
    }
}
