<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeInterface;

class CompanyAccountCompatibilityValidatorRule implements MerchantRelationValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT = 'merchant_relation_request.validation.incompatible_company_account';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeInterface
     */
    protected MerchantRelationRequestToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeInterface
     */
    protected MerchantRelationRequestToCompanyUserFacadeInterface $companyUserFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface
     */
    protected AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantRelationRequestToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        MerchantRelationRequestToCompanyUserFacadeInterface $companyUserFacade,
        AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
    ) {
        $this->errorAdder = $errorAdder;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUserFacade = $companyUserFacade;
        $this->assigneeCompanyBusinessUnitExtractor = $assigneeCompanyBusinessUnitExtractor;
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
            if (!$this->isCompanyAccountCompatible($merchantRelationRequestTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT,
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
    protected function isCompanyAccountCompatible(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): bool {
        $companyBusinessUnitIds = $this->extractCompanyBusinessUnitIds($merchantRelationRequestTransfer);
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getFkCompanyOrFail())
            ->setCompanyBusinessUnitIds($companyBusinessUnitIds)
            ->setWithoutExpanders(true);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfers */
        $companyBusinessUnitTransfers = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
            ->getCompanyBusinessUnits();

        if (count($companyBusinessUnitIds) !== $companyBusinessUnitTransfers->count()) {
            return false;
        }

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->addCompanyUserIds($merchantRelationRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail())
            ->setIdCompany($merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getFkCompanyOrFail());

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\CompanyUserTransfer> $companyUserTransfers */
        $companyUserTransfers = $this->companyUserFacade
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer)
            ->getCompanyUsers();

        return (bool)$companyUserTransfers->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<int>
     */
    protected function extractCompanyBusinessUnitIds(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        $companyBusinessUnitIds = $this->assigneeCompanyBusinessUnitExtractor
            ->extractCompanyBusinessUnitIds($merchantRelationRequestTransfer);

        $companyBusinessUnitIds[] = $merchantRelationRequestTransfer
            ->getOwnerCompanyBusinessUnitOrFail()
            ->getIdCompanyBusinessUnitOrFail();

        return array_unique($companyBusinessUnitIds);
    }
}
