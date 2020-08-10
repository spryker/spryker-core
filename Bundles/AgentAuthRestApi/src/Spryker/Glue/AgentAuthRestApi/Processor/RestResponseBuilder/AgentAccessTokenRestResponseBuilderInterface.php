<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AgentAccessTokenRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentAccessTokensRestResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentCustomerImpersonationAccessTokensRestResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer $customerAutocompleteResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentCustomerSearchRestResponse(
        CustomerAutocompleteResponseTransfer $customerAutocompleteResponseTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidCredentialsErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedToImpersonateCustomerErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createActionAvailableForAgentsOnlyErrorResponse(): RestResponseInterface;
}
