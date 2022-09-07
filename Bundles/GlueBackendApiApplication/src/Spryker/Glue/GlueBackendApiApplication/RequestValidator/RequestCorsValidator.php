<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\RequestValidator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestCorsValidator implements RequestValidatorInterface
{
    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_HEADERS = 'access-control-request-headers';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'access-control-allow-methods';

    /**
     * @var string
     */
    protected const HEADER_ORIGIN = 'origin';

    /**
     * @var string
     */
    protected const METHOD_GET_COLLECTION = 'get_collection';

    protected GlueBackendApiApplicationConfig $config;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig $config
     */
    public function __construct(GlueBackendApiApplicationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): GlueRequestValidationTransfer {
        $headers = $glueRequestTransfer->getMeta();

        $corsMethodValidation = $this->validateCorsMethod($headers, $resource);

        if ($corsMethodValidation === null) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        if (!$corsMethodValidation->getIsValid()) {
            return $corsMethodValidation;
        }

        return $this->validateHeaders($headers);
    }

    /**
     * @param array<mixed> $headers
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validateHeaders(array $headers): GlueRequestValidationTransfer
    {
        if (!isset($headers[static::HEADER_ORIGIN])) {
            $glueErrorTransfer = (new GlueErrorTransfer())
                ->setMessage('The required header `origin` for the options method is missing.')
                ->setStatus(Response::HTTP_NOT_IMPLEMENTED);

            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->addError($glueErrorTransfer)
                ->setStatus(Response::HTTP_NOT_IMPLEMENTED);
        }

        if (empty($headers[static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS])) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $requestedHeaders = explode(', ', (string)current($headers[static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]));
        $requestedHeaders = array_map('strtolower', $requestedHeaders);
        $allowedHeaders = array_map('strtolower', $this->config->getCorsAllowedHeaders());

        foreach ($requestedHeaders as $requestedHeader) {
            if (in_array($requestedHeader, $allowedHeaders, false)) {
                continue;
            }

            $glueErrorTransfer = (new GlueErrorTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setMessage('Not allowed.');

            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->addError($glueErrorTransfer);
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param array<mixed> $headers
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer|null
     */
    protected function validateCorsMethod(array $headers, ResourceInterface $resource): ?GlueRequestValidationTransfer
    {
        $headers[static::HEADER_ACCESS_CONTROL_REQUEST_METHOD] ??= [];
        $method = strtoupper(current($headers[static::HEADER_ACCESS_CONTROL_REQUEST_METHOD]));

        if (!$method || $method === Request::METHOD_OPTIONS) {
            return null;
        }

        $validationResult = (new GlueRequestValidationTransfer());

        $availableMethods = $this->getAvailableMethods($resource);

        if (!in_array($method, $availableMethods)) {
            $glueErrorTransfer = (new GlueErrorTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode('Not allowed.');

            return $validationResult
                ->setIsValid(false)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->addError($glueErrorTransfer);
        }

        return $validationResult->setIsValid(true);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return array<mixed>
     */
    protected function getAvailableMethods(ResourceInterface $resource): array
    {
        $availableMethods = array_keys(array_filter($resource->getDeclaredMethods()->toArray()));

        $index = array_search(static::METHOD_GET_COLLECTION, $availableMethods);

        if ($index !== false) {
            unset($availableMethods[$index]);
            $availableMethods[] = Request::METHOD_GET;
        }

        return array_map('strtoupper', array_unique($availableMethods));
    }
}
