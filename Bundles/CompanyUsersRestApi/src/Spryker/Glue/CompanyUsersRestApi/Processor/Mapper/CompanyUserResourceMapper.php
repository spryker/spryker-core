<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyUserResourceMapper implements CompanyUserResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface[]
     */
    protected $companyUsersResourceMapperPlugins;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface[] $companyUsersResourceMapperPlugins
     */
    public function __construct(array $companyUsersResourceMapperPlugins)
    {
        $this->companyUsersResourceMapperPlugins = $companyUsersResourceMapperPlugins;
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
        foreach ($this->companyUsersResourceMapperPlugins as $companyUsersResourceMapperPlugin) {
            $restCompanyUserAttributesTransfer = $companyUsersResourceMapperPlugin->mapCompanyUserAttributes(
                $companyUserTransfer,
                $restCompanyUserAttributesTransfer
            );
        }

        return $restCompanyUserAttributesTransfer;
    }
}
