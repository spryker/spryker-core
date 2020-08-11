<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserMapper implements RestUserMapperInterface
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
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function mapAgentDataToRestUserTransfer(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        $decodedOauthUserId = $this->agentAuthorizationHeaderReader->getDecodedOauthUserIdentifier($restRequest);

        if ($decodedOauthUserId && isset($decodedOauthUserId['id_agent'])) {
            $restUserTransfer->setIdAgent($decodedOauthUserId['id_agent']);
        }

        return $restUserTransfer;
    }
}
