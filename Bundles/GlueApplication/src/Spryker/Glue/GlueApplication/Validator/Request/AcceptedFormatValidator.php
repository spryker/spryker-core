<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Validator\Request;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Symfony\Component\HttpFoundation\Response;

class AcceptedFormatValidator implements RequestValidatorInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface>
     */
    protected array $responseEncoderStrategies = [];

    /**
     * @param array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface> $responseEncoderStrategies
     */
    public function __construct(array $responseEncoderStrategies)
    {
        $this->responseEncoderStrategies = $responseEncoderStrategies;
    }

    /**
     * Specification:
     * - Checks that data in `GlueRequestTransfer` is valid.
     * - Validates if any of the `GlueRequestTransfer.acceptedFormats` can be served by REST API convention.
     * - Returns error if there is no `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseEncoderPluginInterface`
     * able to serve at least one of the `GlueRequestTransfer.acceptedFormats`.
     * - Does not error if `GlueRequestTransfer.acceptedFormats` is empty.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        if (!$glueRequestTransfer->getAcceptedFormat()) {
            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE)
                ->addError($this->createUnsupportedAcceptFormatGlueError());
        }

        foreach ($this->responseEncoderStrategies as $responseEncoderStrategy) {
            if ($glueRequestTransfer->getAcceptedFormat() === $responseEncoderStrategy->getAcceptedType()) {
                return (new GlueRequestValidationTransfer())->setIsValid(true);
            }
        }

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE)
            ->addError($this->createUnsupportedAcceptFormatGlueError());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnsupportedAcceptFormatGlueError(): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE)
            ->setCode(GlueApplicationConfig::ERROR_CODE_UNSUPPORTED_ACCEPT_FORMAT)
            ->setMessage(GlueApplicationConfig::ERROR_MESSAGE_UNSUPPORTED_ACCEPT_FORMAT);
    }
}
