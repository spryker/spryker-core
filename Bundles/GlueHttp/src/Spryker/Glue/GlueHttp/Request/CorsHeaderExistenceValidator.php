<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\Request;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Symfony\Component\HttpFoundation\Response;

class CorsHeaderExistenceValidator implements CorsHeaderExistenceValidatorInterface
{
    /**
     * @var string
     */
    protected const HEADER_ORIGIN = 'origin';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_HEADERS = 'access-control-request-headers';

    /**
     * @var string
     */
    protected const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        if ($glueRequestTransfer->getMethod() !== static::METHOD_OPTIONS) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $headers = $glueRequestTransfer->getMeta();

        if (
            !isset($headers[static::HEADER_ACCESS_CONTROL_REQUEST_METHOD]) ||
            !isset($headers[static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]) ||
            !isset($headers[static::HEADER_ORIGIN])
        ) {
            $glueErrorTransfer = (new GlueErrorTransfer())
                ->setMessage('One or more of the required headers (access-control-request-method, access-control-request-headers, origin) for the options method are missing.')
                ->setStatus(Response::HTTP_NOT_IMPLEMENTED);

            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->addError($glueErrorTransfer)
                ->setStatus(Response::HTTP_NOT_IMPLEMENTED);
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }
}
