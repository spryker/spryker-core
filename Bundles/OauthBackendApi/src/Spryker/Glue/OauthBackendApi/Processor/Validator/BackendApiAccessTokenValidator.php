<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractorInterface;
use Symfony\Component\HttpFoundation\Response;

class BackendApiAccessTokenValidator implements BackendApiAccessTokenValidatorInterface
{
    /**
     * @var \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractorInterface
     */
    protected $backendAccessTokenExtractor;

    /**
     * @param \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractorInterface $backendAccessTokenExtractor
     */
    public function __construct(
        OauthBackendApiToOauthFacadeInterface $oauthFacade,
        BackendAccessTokenExtractorInterface $backendAccessTokenExtractor
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->backendAccessTokenExtractor = $backendAccessTokenExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = new GlueRequestValidationTransfer();

        if (!$this->backendAccessTokenExtractor->isAuthorizationHeaderSet($glueRequestTransfer)) {
            return $glueRequestValidationTransfer->setIsValid(true);
        }

        $accessTokenData = $this->backendAccessTokenExtractor->extract($glueRequestTransfer);

        if ($accessTokenData === null) {
            return $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_FORBIDDEN)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setStatus(Response::HTTP_FORBIDDEN)
                        ->setCode(OauthBackendApiConfig::RESPONSE_CODE_FORBIDDEN)
                        ->setMessage(OauthBackendApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN),
                );
        }

        $oauthAccessTokenValidationRequestTransfer = $this->validateAccessToken($accessTokenData);

        if ($oauthAccessTokenValidationRequestTransfer->getIsValid() === true) {
            return $glueRequestValidationTransfer->setIsValid(true);
        }

        return $glueRequestValidationTransfer
            ->setIsValid(false)
            ->setStatus(Response::HTTP_UNAUTHORIZED)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_UNAUTHORIZED)
                    ->setCode(OauthBackendApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID)
                    ->setMessage(OauthBackendApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN),
            );
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

        return $this->oauthFacade->validateAccessToken($oauthAccessTokenValidationRequestTransfer);
    }
}
