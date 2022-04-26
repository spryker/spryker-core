<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthDummy\Business\Generator;

use DateInterval;
use DateTimeImmutable;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Spryker\Zed\OauthDummy\OauthDummyConfig;

class AccessTokenGenerator implements AccessTokenGeneratorInterface
{
    /**
     * @var \Spryker\Zed\OauthDummy\OauthDummyConfig
     */
    protected $oauthDummyConfig;

    /**
     * @param \Spryker\Zed\OauthDummy\OauthDummyConfig $oauthDummyConfig
     */
    public function __construct(OauthDummyConfig $oauthDummyConfig)
    {
        $this->oauthDummyConfig = $oauthDummyConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function generateAccessToken(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenResponseTransfer {
        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file($this->oauthDummyConfig->getPathToPrivateKey()),
            InMemory::file($this->oauthDummyConfig->getPathToPublicKey()),
        );

        $expiredAt = (new DateTimeImmutable())
            ->add(new DateInterval(sprintf('PT%sS', $this->oauthDummyConfig->getExpiresIn())));

        $tokenBuilder = $configuration->builder()
            ->relatedTo($this->oauthDummyConfig->getSubject())
            ->issuedAt(new DateTimeImmutable())
            ->expiresAt($expiredAt);

        if ($accessTokenRequestTransfer->getAccessTokenRequestOptions()) {
            if ($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getAudience()) {
                $tokenBuilder->permittedFor($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getAudience());
            }
            if ($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference()) {
                $tokenBuilder->withClaim(
                    $this->oauthDummyConfig->getStoreReferenceKey(),
                    $accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference(),
                );
            }
        }

        foreach ($this->oauthDummyConfig->getAccessTokenCustomClaims() as $name => $value) {
            $tokenBuilder->withClaim($name, $value);
        }

        $token = $tokenBuilder->getToken($configuration->signer(), $configuration->signingKey());

        return (new AccessTokenResponseTransfer())
            ->setIsSuccessful(true)
            ->setAccessToken($token->toString())
            ->setExpiresAt($token->claims()->get('exp')->getTimestamp());
    }
}
