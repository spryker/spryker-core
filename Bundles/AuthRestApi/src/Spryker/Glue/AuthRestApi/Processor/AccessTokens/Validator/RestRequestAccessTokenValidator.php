<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens\Validator;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestRequestAccessTokenValidator implements RestRequestAccessTokenValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $authorizationToken = $request->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return null;
        }
        [$type, $accessToken] = $this->extractToken($authorizationToken);

        if ($accessToken && !$this->isRestUserSet($restRequest)) {
            return $this->createValidationResponse(
                AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                Response::HTTP_UNAUTHORIZED,
                AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID
            );
        }

        return null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return array
     */
    protected function extractToken(string $authorizationToken): array
    {
        return preg_split('/\s+/', $authorizationToken);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isRestUserSet(RestRequestInterface $restRequest): bool
    {
        return ($restRequest->getUser() !== null);
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function createValidationResponse(
        string $detail,
        int $status,
        string $code
    ): RestErrorCollectionTransfer {
        return (new RestErrorCollectionTransfer())
            ->addRestError(
                (new RestErrorMessageTransfer())
                    ->setDetail($detail)
                    ->setStatus($status)
                    ->setCode($code)
            );
    }
}
