<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SimultaneousAuthenticationRestRequestValidator implements SimultaneousAuthenticationRestRequestValidatorInterface
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
        $xAnonymousCustomerUniqueId = $request->headers->get(AuthRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);

        if ($authorizationToken && $xAnonymousCustomerUniqueId) {
            return (new RestErrorCollectionTransfer())->addRestError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(AuthRestApiConfig::RESPONSE_CODE_ANONYMOUS_USER_WITH_ACCESS_TOKEN)
                    ->setDetail(AuthRestApiConfig::RESPONSE_DETAIL_MESSAGE_ANONYMOUS_USER_WITH_ACCESS_TOKEN),
            );
        }

        return null;
    }
}
