<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Route;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectedRouteValidator implements ProtectedRouteValidatorInterface
{
    protected const RESPONSE_DETAIL_MISSING_ACCESS_TOKEN = 'Access denied.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $isProtected = $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_IS_PROTECTED, false);

        //todo: define response code code
        if (!$restRequest->getUser() && $isProtected === true) {
            return $this->createValidationResponse(
                static::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN,
                Response::HTTP_FORBIDDEN,
                ''
            );
        }

        return null;
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
