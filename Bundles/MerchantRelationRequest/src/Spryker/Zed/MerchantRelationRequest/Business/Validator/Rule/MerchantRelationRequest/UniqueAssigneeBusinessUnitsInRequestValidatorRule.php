<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;

class UniqueAssigneeBusinessUnitsInRequestValidatorRule implements MerchantRelationValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_DUPLICATED = 'merchant_relation_request.validation.assignee_business_units_duplicated';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantRelationRequestTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            if ($this->hasDuplicatedAssigneeBusinessUnits($merchantRelationRequestTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_DUPLICATED,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    public function hasDuplicatedAssigneeBusinessUnits(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        $companyBusinessUnitIds = [];
        foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return count($companyBusinessUnitIds) !== count(array_unique($companyBusinessUnitIds));
    }
}
