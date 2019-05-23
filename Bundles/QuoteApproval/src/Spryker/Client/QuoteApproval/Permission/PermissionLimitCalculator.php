<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Client\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;

class PermissionLimitCalculator implements PermissionLimitCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculateApproveQuotePermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->calculateLimitForQuoteByPermissionAndCompanyUser(
            $quoteTransfer,
            $companyUserTransfer,
            ApproveQuotePermissionPlugin::KEY,
            ApproveQuotePermissionPlugin::FIELD_STORE_MULTI_CURRENCY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculatePlaceOrderPermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->calculateLimitForQuoteByPermissionAndCompanyUser(
            $quoteTransfer,
            $companyUserTransfer,
            PlaceOrderPermissionPlugin::KEY,
            PlaceOrderPermissionPlugin::FIELD_STORE_MULTI_CURRENCY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $permissionKey
     * @param string $configurationKey
     *
     * @return int|null
     */
    protected function calculateLimitForQuoteByPermissionAndCompanyUser(
        QuoteTransfer $quoteTransfer,
        CompanyUserTransfer $companyUserTransfer,
        string $permissionKey,
        string $configurationKey
    ) {
        $highestLimit = null;
        $isLimitUpdated = false;

        foreach ($companyUserTransfer->getCompanyRoleCollection()->getRoles() as $companyRoleTransfer) {
            $permissionTransfer = $this->findPermissionByKey(
                $companyRoleTransfer->getPermissionCollection(),
                $permissionKey
            );

            if ($permissionTransfer === null) {
                continue;
            }

            $limit = $this->findPermissionLimitForQuote(
                $permissionTransfer,
                $quoteTransfer,
                $configurationKey
            );

            if (!$isLimitUpdated || $limit > $highestLimit) {
                $highestLimit = $limit;
                $isLimitUpdated = true;
            }
        }

        return $highestLimit;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permission
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $configurationKey
     *
     * @return int|null
     */
    protected function findPermissionLimitForQuote(
        PermissionTransfer $permission,
        QuoteTransfer $quoteTransfer,
        string $configurationKey
    ): ?int {
        $configuration = $permission->getConfiguration();
        $currencyCode = $quoteTransfer->getCurrency()->getCode();
        $storeName = $quoteTransfer->getStore()->getName();

        return $configuration[$configurationKey][$storeName][$currencyCode] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    protected function findPermissionByKey(
        PermissionCollectionTransfer $permissionCollectionTransfer,
        string $permissionKey
    ): ?PermissionTransfer {
        foreach ($permissionCollectionTransfer->getPermissions() as $permission) {
            if ($permission->getKey() === $permissionKey) {
                return $permission;
            }
        }

        return null;
    }
}
