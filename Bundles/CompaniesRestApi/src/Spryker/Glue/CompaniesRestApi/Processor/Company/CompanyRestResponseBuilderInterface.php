<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company;

use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CompanyRestResponseBuilderInterface
{
    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\RestCompanyAttributesTransfer $restCompanyAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyRestResponse(
        string $uuid,
        RestCompanyAttributesTransfer $restCompanyAttributesTransfer
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUuidMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyNotFoundError(): RestResponseInterface;
}
