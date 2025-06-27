<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SelfServicePortal\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

class UnassignSspAssetPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface
{
    /**
     * @var string
     */
    public const CONTEXT_COMPANY_USER = 'company_user';

    /**
     * @var string
     */
    public const CONTEXT_SSP_ASSET = 'ssp_asset';

    /**
     * @var string
     */
    public const KEY = 'UnassignSspAssetPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }

    /**
     * {@inheritDoc}
     * - Checks if the business unit of the company user matches any business unit associated with the SSP asset.
     *
     * @param array<string, mixed> $configuration
     * @param array<string, mixed>|null $context
     *
     * @return bool
     */
    public function can(array $configuration, $context = null): bool
    {
        if (!isset($context[static::CONTEXT_SSP_ASSET])) {
            return true;
        }

        /**
         * @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
         */
        $companyUserTransfer = $context[static::CONTEXT_COMPANY_USER];

        /**
         * @var \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
         */
        $sspAssetTransfer = $context[static::CONTEXT_SSP_ASSET];

        if ($sspAssetTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit() === $companyUserTransfer->getFkCompanyBusinessUnitOrFail()) {
            return true;
        }

        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspAssetBusinessUnitAssignmentTransfer) {
            if ($sspAssetBusinessUnitAssignmentTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail() === $companyUserTransfer->getFkCompanyBusinessUnitOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string>
     */
    public function getConfigurationSignature(): array
    {
        return [];
    }
}
