<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;

abstract class BaseOauthAccessTokenRestRequestValidator implements OauthAccessTokenValidatorInterface
{
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     */
    public function __construct(AuthRestApiToOauthClientInterface $oauthClient)
    {
        $this->oauthClient = $oauthClient;
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(string $detail, int $status, string $code): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
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

    /**
     * @param string $authorizationToken
     *
     * @return bool
     */
    protected function validateAccessToken(string $authorizationToken): bool
    {
        $accessToken = $this->extractToken($authorizationToken);
        $type = $this->extractTokenType($authorizationToken);
        if (!$accessToken || !$type) {
            return false;
        }

        $authAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $authAccessTokenValidationRequestTransfer
            ->setAccessToken($accessToken)
            ->setType($type);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient->validateOauthAccessToken(
            $authAccessTokenValidationRequestTransfer
        );

        return $authAccessTokenValidationResponseTransfer->getIsValid();
    }
}
