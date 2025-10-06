<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Checker;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToCompanyFacadeInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class EditCompanyOrdersPermissionChecker implements EditCompanyOrdersPermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\EditCompanyOrdersPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY = 'EditCompanyOrdersPermissionPlugin';

    /**
     * @param \Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToCompanyFacadeInterface $companyFacade
     */
    public function __construct(protected CompanySalesConnectorToCompanyFacadeInterface $companyFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isEditCompanyOrderCartReorderAllowed(
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): bool {
        if (!$this->isValidCompanyUser($cartReorderRequestTransfer)) {
            return false;
        }

        $companyUserTransfer = $cartReorderRequestTransfer->getCompanyUserTransferOrFail();

        return $this->isEditCompanyOrderAllowed($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isEditCompanyOrderAllowed(CompanyUserTransfer $companyUserTransfer): bool
    {
        if (!$this->hasEditOrderPermission($companyUserTransfer)) {
            return false;
        }

        $companyUserTransfer = $this->expandWithCompany($companyUserTransfer);

        return (bool)$companyUserTransfer->getCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function isOrderBelongsToCompany(?OrderTransfer $orderTransfer, CompanyUserTransfer $companyUserTransfer): bool
    {
        if (!$orderTransfer || !$orderTransfer->getCompanyUuid()) {
            return false;
        }

        return $orderTransfer->getCompanyUuid() === $companyUserTransfer->getCompany()->getUuid();
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    protected function isValidCompanyUser(CartReorderRequestTransfer $cartReorderRequestTransfer): bool
    {
        $companyUserTransfer = $cartReorderRequestTransfer->getCompanyUserTransfer();

        return (bool)$companyUserTransfer?->getIdCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function hasEditOrderPermission(CompanyUserTransfer $companyUserTransfer): bool
    {
        return $this->can(static::PERMISSION_KEY, $companyUserTransfer->getIdCompanyUserOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function expandWithCompany(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        if (!$companyUserTransfer->getCompany() && $companyUserTransfer->getFkCompany()) {
            $companyUserTransfer->setCompany(
                $this->companyFacade->findCompanyById($companyUserTransfer->getFkCompanyOrFail()),
            );
        }

        return $companyUserTransfer;
    }
}
