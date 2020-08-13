<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Finder;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserFinder implements RestUserFinderInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface
     */
    protected $agentAuthorizationHeaderReader;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface $agentAuthorizationHeaderReader
     */
    public function __construct(AgentAuthorizationHeaderReaderInterface $agentAuthorizationHeaderReader)
    {
        $this->agentAuthorizationHeaderReader = $agentAuthorizationHeaderReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findAgentRestUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        $agentAccessTokenHeader = $restRequest->getHttpRequest()->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$agentAccessTokenHeader) {
            return null;
        }

        $idAgent = $this->agentAuthorizationHeaderReader->getIdAgentFromOauthAccessToken($restRequest);

        if (!$idAgent) {
            null;
        }

        return (new RestUserTransfer())->setIdAgent($idAgent);
    }
}
