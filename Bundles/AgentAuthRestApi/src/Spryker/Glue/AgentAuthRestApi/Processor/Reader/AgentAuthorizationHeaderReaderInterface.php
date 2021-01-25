<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

interface AgentAuthorizationHeaderReaderInterface
{
    /**
     * @param string $agentAccessTokenHeader
     *
     * @return int|null
     */
    public function getIdAgentFromOauthAccessToken(string $agentAccessTokenHeader): ?int;

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    public function extractToken(string $authorizationToken): ?string;

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    public function extractTokenType(string $authorizationToken): ?string;
}
