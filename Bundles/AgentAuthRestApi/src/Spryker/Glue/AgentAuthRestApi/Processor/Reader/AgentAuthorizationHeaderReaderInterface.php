<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AgentAuthorizationHeaderReaderInterface
{
    /**
     * @phpstan-return array<int|string>|null
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array|null
     */
    public function getDecodedOauthUserIdentifier(RestRequestInterface $restRequest): ?array;

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
