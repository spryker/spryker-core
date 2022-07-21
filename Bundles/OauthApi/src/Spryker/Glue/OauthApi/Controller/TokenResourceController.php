<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Controller;

use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\ApiTokenResponseAttributesTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Glue\Kernel\Controller\AbstractStorefrontApiController;
use Spryker\Glue\OauthApi\OauthApiConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\OauthApi\OauthApiFactory getFactory()
 */
class TokenResourceController extends AbstractStorefrontApiController
{
    /**
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * @param \Generated\Shared\Transfer\ApiTokenAttributesTransfer $apiTokenAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        ApiTokenAttributesTransfer $apiTokenAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->fromArray($apiTokenAttributesTransfer->toArray(), true);

        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_STOREFRONT_API_APPLICATION);

        $glueAuthenticationRequestTransfer = (new GlueAuthenticationRequestTransfer())
            ->setOauthRequest($oauthRequestTransfer)
            ->setRequestContext($glueAuthenticationRequestContextTransfer);

        $glueAuthenticationResponseTransfer = $this->getFactory()->getAuthenticationClient()->authenticate($glueAuthenticationRequestTransfer);

        return $this->mapAuthenticationAttributesToGlueResponseTransfer($glueAuthenticationResponseTransfer, $glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer $glueAuthenticationResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function mapAuthenticationAttributesToGlueResponseTransfer(
        GlueAuthenticationResponseTransfer $glueAuthenticationResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        if ($glueAuthenticationResponseTransfer->getOauthResponseOrFail()->getIsValid() === false) {
            $glueResponseTransfer
                ->setHttpStatus(Response::HTTP_BAD_REQUEST)
                ->addError((new GlueErrorTransfer())
                    ->setMessage($glueAuthenticationResponseTransfer->getOauthResponseOrFail()->getErrorOrFail()->getMessage())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode($glueAuthenticationResponseTransfer->getOauthResponseOrFail()->getErrorOrFail()->getErrorType()));

            return $glueResponseTransfer;
        }

        $resourceTransfer = (new GlueResourceTransfer())
            ->setType(OauthApiConfig::RESOURCE_TOKEN)
            ->setAttributes(
                (new ApiTokenResponseAttributesTransfer())
                ->fromArray($glueAuthenticationResponseTransfer->getOauthResponseOrFail()->toArray(), true),
            );
        $glueResponseTransfer->addResource($resourceTransfer);

        return $glueResponseTransfer;
    }
}
