<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestValidator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Symfony\Component\HttpFoundation\Response;

class AcceptedFormatValidator implements AcceptedFormatValidatorInterface
{
    /**
     * @var array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    protected array $responseEncoderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface> $responseEncoderPlugins
     */
    public function __construct(array $responseEncoderPlugins)
    {
        $this->responseEncoderPlugins = $responseEncoderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        if (!$glueRequestTransfer->getAcceptedFormat()) {
            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->addError($this->createUnsupportedAcceptFormatGlueError());
        }

        foreach ($this->responseEncoderPlugins as $responseEncoderPlugin) {
            if (in_array($glueRequestTransfer->getAcceptedFormat(), $responseEncoderPlugin->getAcceptedFormats())) {
                return (new GlueRequestValidationTransfer())->setIsValid(true);
            }
        }

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->addError($this->createUnsupportedAcceptFormatGlueError());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnsupportedAcceptFormatGlueError(): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setCode(GlueRestApiConventionConfig::ERROR_CODE_UNSUPPORTED_ACCEPT_FORMAT)
            ->setMessage(GlueRestApiConventionConfig::ERROR_MESSAGE_UNSUPPORTED_ACCEPT_FORMAT_MESSAGE);
    }
}
