<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface;

class MerchantReferenceExistsValidatorRule implements MerchantRelationshipValidatorRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantRelationshipToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
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
        if ($merchantRelationshipTransfer->getFkMerchant()) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        if (!$merchantRelationshipTransfer->getMerchant() || !$merchantRelationshipTransfer->getMerchantOrFail()->getMerchantReference()) {
            $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
                MerchantTransfer::MERCHANT_REFERENCE,
                sprintf('"%s" field is empty.', MerchantTransfer::MERCHANT_REFERENCE),
            );

            return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
        }

        $merchantReference = $merchantRelationshipTransfer->getMerchantOrFail()->getMerchantReference();
        $existingMerchantTransfer = $this->merchantFacade->findOne(
            $this->createMerchantCriteriaTransfer($merchantReference),
        );

        if ($existingMerchantTransfer) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
            MerchantTransfer::MERCHANT_REFERENCE,
            sprintf('Merchant reference `%s` does not exist.', $merchantReference),
        );

        return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantCriteriaTransfer
     */
    protected function createMerchantCriteriaTransfer(string $merchantReference): MerchantCriteriaTransfer
    {
        return (new MerchantCriteriaTransfer())->setMerchantReference($merchantReference);
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
