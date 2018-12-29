<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
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
        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();
        if ($companyBusinessUnitTransfer === null) {
            return $restCompanyUserAttributesTransfer;
        }

        $restCompanyBusinessUnitAttributesTransfer = (new RestCompanyBusinessUnitAttributesTransfer())
            ->fromArray($companyBusinessUnitTransfer->toArray(), true);

        $restCompanyBusinessUnitAttributesTransfer = $this->executeCompanyBusinessUnitAttributesMapperPlugins(
            $companyBusinessUnitTransfer,
            $restCompanyBusinessUnitAttributesTransfer
        );

        $restCompanyUserAttributesTransfer->setCompanyBusinessUnit($restCompanyBusinessUnitAttributesTransfer);

        return $restCompanyUserAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer
     */
    protected function executeCompanyBusinessUnitAttributesMapperPlugins(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
    ): RestCompanyBusinessUnitAttributesTransfer {
        foreach ($this->companyBusinessUnitAttributesMapperPlugins as $companyBusinessUnitAttributesMapperPlugin) {
            $restCompanyBusinessUnitAttributesTransfer = $companyBusinessUnitAttributesMapperPlugin->mapCompanyBusinessUnitAttributes(
                $companyBusinessUnitTransfer,
                $restCompanyBusinessUnitAttributesTransfer
            );
        }

        return $restCompanyBusinessUnitAttributesTransfer;
    }
}
