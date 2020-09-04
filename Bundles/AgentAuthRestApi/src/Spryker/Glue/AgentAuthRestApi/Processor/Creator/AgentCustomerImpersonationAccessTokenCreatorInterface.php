<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Creator;

use Generated\Shared\Transfer\RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AgentCustomerImpersonationAccessTokenCreatorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer $restAgentCustomerImpersonationAccessTokensRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(
        RestRequestInterface $restRequest,
        RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer $restAgentCustomerImpersonationAccessTokensRequestAttributesTransfer
    ): RestResponseInterface;
}
