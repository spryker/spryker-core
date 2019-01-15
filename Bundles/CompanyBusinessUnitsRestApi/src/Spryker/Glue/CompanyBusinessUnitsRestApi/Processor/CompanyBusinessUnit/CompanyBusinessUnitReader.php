<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface
     */
    protected $companyBusinessUnitMapperInterface;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface
     */
    public function __construct(
        CompanyBusinessUnitMapperInterface $companyBusinessUnitMapperInterface
    )
    {
        $this->companyBusinessUnitMapperInterface = $companyBusinessUnitMapperInterface;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyBusinessUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapperInterface
            ->mapCompanyBusinessUnitAttributesTransferToRestCompanyBusinessUnitAttributesTransfer(
                $companyBusinessUnitTransfer
            );

        return $restCompanyBusinessUnitAttributesTransfer;
    }
}
