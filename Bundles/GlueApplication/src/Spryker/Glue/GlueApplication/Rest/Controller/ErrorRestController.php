<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Controller;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Controller\ErrorControllerInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ErrorRestController extends AbstractRestController implements ErrorControllerInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function badRequestAction(): RestResponseInterface
    {
        $response = new RestResponse();

        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(Response::$statusTexts[Response::HTTP_BAD_REQUEST]);

        $response->addError($restErrorMessageTransfer);

        return $response;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function resourceNotFoundAction(): RestResponseInterface
    {
        $response = new RestResponse();

        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(Response::$statusTexts[Response::HTTP_NOT_FOUND]);

        $response->addError($restErrorMessageTransfer);

        return $response;
    }
}
