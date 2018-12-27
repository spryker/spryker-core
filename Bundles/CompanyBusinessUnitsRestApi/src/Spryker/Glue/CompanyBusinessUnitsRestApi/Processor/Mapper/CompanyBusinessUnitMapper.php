<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;

class CompanyBusinessUnitMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitAttributesMapperPluginInterface[]
     */
    protected $companyBusinessUnitAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitAttributesMapperPluginInterface[] $companyBusinessUnitAttributesMapperPlugins
     */
    public function __construct(array $companyBusinessUnitAttributesMapperPlugins)
    {
        $this->companyBusinessUnitAttributesMapperPlugins = $companyBusinessUnitAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    public function mapCompanyUserAttributes(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        $restCompanyUserAttributesTransfer->setCompanyBusinessUnit(
            (new RestCompanyBusinessUnitAttributesTransfer())
                ->fromArray($companyUserTransfer->toArray(), true)
        );
        $restCompanyUserAttributesTransfer = $this->executeCompanyBusinessUnitMapperPlugins($companyUserTransfer, $restCompanyUserAttributesTransfer);

        return $restCompanyUserAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    protected function executeCompanyBusinessUnitMapperPlugins(
        CompanyUserTransfer $companyUserTransfer,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
    ): RestCompanyUserAttributesTransfer {
        foreach ($this->companyBusinessUnitAttributesMapperPlugins as $companyBusinessUnitAttributesMapperPlugin) {
            $restCompanyUserAttributesTransfer->setCompanyBusinessUnit(
                $companyBusinessUnitAttributesMapperPlugin->mapCompanyBusinessUnitAttributes(
                    $companyUserTransfer->getCompanyBusinessUnit(),
                    $restCompanyUserAttributesTransfer->getCompanyBusinessUnit()
                )
            );
        }

        return $restCompanyUserAttributesTransfer;
    }
}
