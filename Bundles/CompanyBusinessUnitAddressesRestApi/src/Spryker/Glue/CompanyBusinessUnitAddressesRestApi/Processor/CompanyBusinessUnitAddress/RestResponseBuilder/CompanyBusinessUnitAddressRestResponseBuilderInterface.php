<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder;

use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CompanyBusinessUnitAddressRestResponseBuilderInterface
{
    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressRestResponse(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function buildCompanyBusinessUnitAddressRestResource(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
    ): RestResourceInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressIdMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressNotFoundError(): RestResponseInterface;
}
