<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\MerchantRelationshipErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface;

class OwnerCompanyBusinessUnitExistsValidatorRule implements MerchantRelationshipValidatorRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(MerchantRelationshipToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
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
        $ownerCompanyBusinessUnitTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit();

        $idOwnerCompanyBusinessUnit = $ownerCompanyBusinessUnitTransfer
            ? $ownerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            : $merchantRelationshipTransfer->getFkCompanyBusinessUnit();

        if (!$ownerCompanyBusinessUnitTransfer && !$idOwnerCompanyBusinessUnit) {
            $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
                'idBusinessUnitOwner',
                '"idBusinessUnitOwner" field is not defined.',
            );

            return $merchantRelationshipValidationErrorCollectionTransfer->addError($merchantRelationshipErrorTransfer);
        }

        $existingCompanyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($idOwnerCompanyBusinessUnit);

        if ($existingCompanyBusinessUnitTransfer) {
            return $merchantRelationshipValidationErrorCollectionTransfer;
        }

        $merchantRelationshipErrorTransfer = $this->createMerchantRelationshipErrorTransfer(
            'idBusinessUnitOwner',
            sprintf('Business unit owner id "%s" does not exist.', $ownerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
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
