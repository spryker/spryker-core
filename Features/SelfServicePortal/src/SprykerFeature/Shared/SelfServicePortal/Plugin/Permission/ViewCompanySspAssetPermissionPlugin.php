<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SelfServicePortal\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

class ViewCompanySspAssetPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface
{
    /**
     * @var string
     */
    public const KEY = 'ViewCompanySspAssetPermissionPlugin';

    /**
     * @var string
     */
    public const CONTEXT_COMPANY_USER = 'company_user';

    /**
     * @var string
     */
    public const CONTEXT_SSP_ASSET = 'ssp_asset';

    /**
     * {@inheritDoc}
     * - Checks if the company user has access to the ssp asset withing the specific company.
     *
     * @param array<string, mixed> $configuration
     * @param array<mixed>|string|int|null $context
     *
     * @return bool
     */
    public function can(array $configuration, $context = null): bool
    {
        if (!isset($context[static::CONTEXT_COMPANY_USER], $context[static::CONTEXT_SSP_ASSET])) {
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

        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspAssetBusinessUnitAssignmentTransfer) {
            if ($sspAssetBusinessUnitAssignmentTransfer->getCompanyBusinessUnitOrFail()->getCompanyOrFail()->getIdCompanyOrFail() === $companyUserTransfer->getFkCompany()) {
                return true;
            }
        }

         return false;
    }

    /**
     * @return array<mixed>
     */
    public function getConfigurationSignature(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
