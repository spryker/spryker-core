<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\Company\Validator\CompanyBusinessUnitValidatorInterface;

class SspServiceCustomerPermissionExpander implements SspServiceCustomerPermissionExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin
     *
     * @var string
     */
    protected const PERMISSION_SEE_COMPANY_ORDERS = 'SeeCompanyOrdersPermissionPlugin';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin
     *
     * @var string
     */
    protected const PERMISSION_SEE_BUSINESS_UNIT_ORDERS = 'SeeBusinessUnitOrdersPermissionPlugin';

    public function __construct(
        protected CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        protected CompanyFacadeInterface $companyFacade,
        protected CompanyBusinessUnitValidatorInterface $companyBusinessUnitValidator
    ) {
    }

    public function expand(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCriteriaTransfer
    {
        $companyUserTransfer = $sspServiceCriteriaTransfer->getCompanyUserOrFail();

        if (!$sspServiceCriteriaTransfer->getServiceConditions()) {
            $sspServiceCriteriaTransfer->setServiceConditions(new SspServiceConditionsTransfer());
        }

        $serviceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditionsOrFail();
        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();
        $companyBusinessUnitUuid = $serviceConditionsTransfer->getCompanyBusinessUnitUuid();

        $serviceConditionsTransfer->setCompanyUuid(null);

        if ($companyBusinessUnitUuid && $this->companyBusinessUnitValidator->isCompanyBusinessUnitUuidBelongsToCompany($companyUserTransfer, $companyBusinessUnitUuid)) {
            $serviceConditionsTransfer->setCompanyBusinessUnitUuid($companyBusinessUnitUuid);

            return $sspServiceCriteriaTransfer;
        }

        if ($this->can(static::PERMISSION_SEE_COMPANY_ORDERS, $idCompanyUser)) {
            $serviceConditionsTransfer->setCompanyUuid($this->getCompanyUuid($companyUserTransfer));

            return $sspServiceCriteriaTransfer;
        }

        if ($this->can(static::PERMISSION_SEE_BUSINESS_UNIT_ORDERS, $idCompanyUser)) {
            $serviceConditionsTransfer->setCompanyBusinessUnitUuid($this->getCompanyBusinessUnitUuid($companyUserTransfer));

            return $sspServiceCriteriaTransfer;
        }

        $serviceConditionsTransfer->setCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());

        return $sspServiceCriteriaTransfer;
    }

    protected function getCompanyBusinessUnitUuid(CompanyUserTransfer $companyUserTransfer): string
    {
        if ($companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuid()) {
            return $companyUserTransfer->getCompanyBusinessUnitOrFail()->getUuid();
        }

        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitById($companyUserTransfer->getCompanyBusinessUnitOrFail())->getUuidOrFail();
    }

    protected function getCompanyUuid(CompanyUserTransfer $companyUserTransfer): string
    {
        if ($companyUserTransfer->getCompanyOrFail()->getUuid()) {
            return $companyUserTransfer->getCompanyOrFail()->getUuid();
        }

        return $this->companyFacade->getCompanyById($companyUserTransfer->getCompanyOrFail())->getUuidOrFail();
    }
}
