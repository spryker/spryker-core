<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyUserMapper implements CompanyUserMapperInterface
{
    /**
     * @var \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserAttributesMapperPluginInterface[]
     */
    protected $companyUserAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserAttributesMapperPluginInterface[] $companyUserAttributesMapperPlugins
     */
    public function __construct(array $companyUserAttributesMapperPlugins)
    {
        $this->companyUserAttributesMapperPlugins = $companyUserAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        $restCompanyUserAttributesTransfer = $restCompanyUserAttributesTransfer
            ->fromArray($companyUserTransfer->toArray(), true);

        $this->executeCompanyUserMapperPlugin(
            $restCompanyUserAttributesTransfer,
            $companyUserTransfer
        );

        return $restCompanyUserAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    protected function executeCompanyUserMapperPlugin(
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): RestCompanyUserAttributesTransfer {
        foreach ($this->companyUserAttributesMapperPlugins as $companyUserAttributesMapperPlugin) {
            $restCompanyUserAttributesTransfer = $companyUserAttributesMapperPlugin->mapCompanyUserAttributes(
                $companyUserTransfer,
                $restCompanyUserAttributesTransfer
            );
        }

        return $restCompanyUserAttributesTransfer;
    }
}
