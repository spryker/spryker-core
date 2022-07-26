<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Processor\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Glue\OauthApi\Dependency\Client\OauthApiToOauthClientInterface;
use Spryker\Glue\OauthApi\OauthApiConfig;
use Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenValidator implements AccessTokenValidatorInterface
{
    /**
     * @var \Spryker\Glue\OauthApi\Dependency\Client\OauthApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    protected $accessTokenExtractor;

    /**
     * @param \Spryker\Glue\OauthApi\Dependency\Client\OauthApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface $accessTokenExtractor
     */
    public function __construct(
        OauthApiToOauthClientInterface $oauthClient,
        AccessTokenExtractorInterface $accessTokenExtractor
    ) {
        $this->oauthClient = $oauthClient;
        $this->accessTokenExtractor = $accessTokenExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();

        if (!$this->accessTokenExtractor->isAuthorizationHeaderSet($glueRequestTransfer)) {
            return $glueRequestValidationTransfer->setIsValid(true);
        }

        $accessTokenData = $this->accessTokenExtractor->extract($glueRequestTransfer);

        if ($accessTokenData === null) {
            return $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_FORBIDDEN)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setStatus(Response::HTTP_FORBIDDEN)
                        ->setCode(OauthApiConfig::RESPONSE_CODE_FORBIDDEN)
                        ->setMessage(OauthApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN),
                );
        }

        $oauthAccessTokenValidationRequestTransfer = $this->validateAccessToken($accessTokenData);

        if (!$oauthAccessTokenValidationRequestTransfer->getIsValid()) {
            $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNAUTHORIZED)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setStatus(Response::HTTP_UNAUTHORIZED)
                        ->setCode(OauthApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID)
                        ->setMessage(OauthApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN),
                );
        }

        $glueRequestValidationTransfer->setIsValid($oauthAccessTokenValidationRequestTransfer->getIsValid());

        return $glueRequestValidationTransfer;
    }

    /**
     * @param array<string> $accessTokenData
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    protected function validateAccessToken(array $accessTokenData): OauthAccessTokenValidationResponseTransfer
    {
        $oauthAccessTokenValidationRequestTransfer = (new OauthAccessTokenValidationRequestTransfer())
            ->setType($accessTokenData[0])
            ->setAccessToken($accessTokenData[1]);

        return $this->oauthClient->validateOauthAccessToken($oauthAccessTokenValidationRequestTransfer);
    }
}
