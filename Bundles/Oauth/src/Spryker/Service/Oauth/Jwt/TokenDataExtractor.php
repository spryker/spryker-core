<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

use Generated\Shared\Transfer\JwtTokenTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;

class TokenDataExtractor implements TokenDataExtractorInterface
{
    protected const JWT_CLAIM_JTI = 'jti';
    protected const JWT_CLAIM_AUD = 'aud';
    protected const JWT_CLAIM_SUB = 'sub';
    protected const JWT_CLAIM_SCOPES = 'scopes';

    /**
     * @var \Spryker\Service\Oauth\Jwt\JwtTokenParserInterface
     */
    protected $jwtTokenParser;

    /**
     * @param \Spryker\Service\Oauth\Jwt\JwtTokenParserInterface $jwtTokenParser
     */
    public function __construct(JwtTokenParserInterface $jwtTokenParser)
    {
        $this->jwtTokenParser = $jwtTokenParser;
    }

    /**
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function extractAccessTokenData(string $accessToken): OauthAccessTokenDataTransfer
    {
        $jwtToken = $this->jwtTokenParser->parse($accessToken);
        if (!$jwtToken) {
            return new OauthAccessTokenDataTransfer();
        }

        return $this->createJwtTokenToOauthTokenDataTransfer($jwtToken);
    }

    /**
     * @param \Generated\Shared\Transfer\JwtTokenTransfer $jwtTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    protected function createJwtTokenToOauthTokenDataTransfer(JwtTokenTransfer $jwtTokenTransfer): OauthAccessTokenDataTransfer
    {
        return (new OauthAccessTokenDataTransfer())
            ->setOauthAccessTokenId($jwtTokenTransfer->getClaims()[static::JWT_CLAIM_JTI] ?? null)
            ->setOauthClientId($jwtTokenTransfer->getClaims()[static::JWT_CLAIM_AUD] ?? null)
            ->setOauthUserId($jwtTokenTransfer->getClaims()[static::JWT_CLAIM_SUB] ?? null)
            ->setOauthScopes($jwtTokenTransfer->getClaims()[static::JWT_CLAIM_SCOPES] ?? null);
    }
}
