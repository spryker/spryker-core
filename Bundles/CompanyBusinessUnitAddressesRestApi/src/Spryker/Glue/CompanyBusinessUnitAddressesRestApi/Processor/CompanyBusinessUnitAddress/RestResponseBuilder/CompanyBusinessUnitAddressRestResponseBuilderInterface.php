<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder;

use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CompanyBusinessUnitAddressRestResponseBuilderInterface
{
    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressRestResponse(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyBusinessUnitAddressRestResource(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
    ): RestResourceInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $companyBusinessUnitAddressRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressCollectionRestResponse(array $companyBusinessUnitAddressRestResources): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressIdMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createResourceNotImplementedError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserNotSelectedError(): RestResponseInterface;
}
