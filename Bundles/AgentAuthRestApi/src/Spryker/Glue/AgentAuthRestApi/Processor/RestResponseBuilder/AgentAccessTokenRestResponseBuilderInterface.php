<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface AgentAccessTokenRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentAccessTokensRestResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidCredentialsErrorResponse(): RestResponseInterface;
}
