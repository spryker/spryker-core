<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Plugin\AuthRestApi;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiFactory getFactory()
 */
class AgentRestUserMapperPlugin extends AbstractPlugin implements RestUserMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps agent data to the rest user identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function map(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        // TODO: move logic to processors
        $agentAccessToken = $restRequest->getHttpRequest()->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$agentAccessToken) {
            return $restUserTransfer;
        }

        $agentAccessToken = $this->extractToken($agentAccessToken);
        $agentAccessTokenType = $this->extractTokenType($agentAccessToken);
        if (!$agentAccessToken || !$agentAccessTokenType) {
            return $restUserTransfer;
        }

        $oauthAccessTokenDataTransfer = $this->getFactory()->getOauthService()
            ->extractAccessTokenData($agentAccessToken);

        $decodedOauthUserId = $this->getFactory()->getUtilEncodingService()
            ->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);

        if ($decodedOauthUserId && isset($decodedOauthUserId['id_agent'])) {
            $restUserTransfer->setIdAgent($decodedOauthUserId['id_agent']);
        }

        return $restUserTransfer;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractToken(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[1] ?? null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractTokenType(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[0] ?? null;
    }
}
