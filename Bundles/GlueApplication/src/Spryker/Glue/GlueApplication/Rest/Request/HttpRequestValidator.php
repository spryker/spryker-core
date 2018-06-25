<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpRequestValidator implements HttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected $requestValidatorPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[] $requestValidatorPlugins
     */
    public function __construct(array $requestValidatorPlugins)
    {
        $this->requestValidatorPlugins = $requestValidatorPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $restErrorMessageTransfer = $this->validateRequiredHeaders($request);
        if (!$restErrorMessageTransfer) {
            $restErrorMessageTransfer = $this->executeRequestValidationPlugins($request);
        }

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateRequiredHeaders(Request $request): ?RestErrorMessageTransfer
    {
        $headerData = $request->headers->all();

        if (!isset($headerData[RequestConstantsInterface::HEADER_ACCEPT])) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not acceptable.')
                ->setStatus(Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($headerData[RequestConstantsInterface::HEADER_CONTENT_TYPE])) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Unsuported media type.')
                ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function executeRequestValidationPlugins(Request $request): ?RestErrorMessageTransfer
    {
        foreach ($this->requestValidatorPlugins as $requestValidatorPlugin) {
            $restErrorMessageTransfer = $requestValidatorPlugin->validate($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            return $restErrorMessageTransfer;
        }
        return null;
    }
}
