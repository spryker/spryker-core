<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Transformer;

use Generated\Shared\Transfer\ApiOptionsTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Http\HttpConstants;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface;
use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class Transformer implements TransformerInterface
{
    /**
     * @var \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface
     */
    protected $formatterResolver;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @var \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface $formatterResolver
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     * @param \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        FormatterResolverInterface $formatterResolver,
        ApiConfig $apiConfig,
        ApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->formatterResolver = $formatterResolver;
        $this->apiConfig = $apiConfig;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transform(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer, Response $response): Response
    {
        $headers = $apiResponseTransfer->getHeaders() + $this->getDefaultResponseHeaders($apiRequestTransfer);
        $response->headers->add($headers);

        $response->setStatusCode($apiResponseTransfer->getCodeOrFail());

        return $this->addResponseContent($apiRequestTransfer, $apiResponseTransfer, $response);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transformBadRequest(ApiResponseTransfer $apiResponseTransfer, Response $response, string $message): Response
    {
        $headers = $apiResponseTransfer->getHeaders() + $this->getDefaultResponseHeaders();
        $response->headers->add($headers);

        $response->setStatusCode(ApiConfig::HTTP_CODE_BAD_REQUEST);
        $response->setContent($this->utilEncodingService->encodeJson([
            'code' => ApiConfig::HTTP_CODE_BAD_REQUEST,
            'message' => $message,
        ]));

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addResponseContent(
        ApiRequestTransfer $apiRequestTransfer,
        ApiResponseTransfer $apiResponseTransfer,
        Response $response
    ): Response {
        if ($this->isContentless($apiResponseTransfer)) {
            return $response;
        }

        $content = [];
        $content['code'] = $apiResponseTransfer->getCode();
        $content['message'] = $apiResponseTransfer->getMessage();
        if ((int)$apiResponseTransfer->getCode() === ApiConfig::HTTP_CODE_VALIDATION_ERRORS) {
            $content = $this->addValidationErrorsToResponseContent($apiResponseTransfer, $content);
        }

        $result = $apiResponseTransfer->getData();
        if ($result !== null) {
            $content['data'] = $result;
        }

        $meta = $apiResponseTransfer->getMeta();
        if ($meta) {
            $content['links'] = $meta->getLinks();
            if ($meta->getSelf()) {
                $content['links']['self'] = $meta->getSelf();
            }
            $content['meta'] = $meta->getData();
        }

        if ($this->apiConfig->isApiDebugEnabled()) {
            $content['_stackTrace'] = $apiResponseTransfer->getStackTrace();
            $content['_request'] = $apiRequestTransfer->toArray();
        }

        $content = $this->formatterResolver
            ->resolveFormatter($apiRequestTransfer->getFormatType())
            ->format($content);
        $response->setContent($content);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return bool
     */
    protected function isContentless(ApiResponseTransfer $apiResponseTransfer): bool
    {
        return (int)$apiResponseTransfer->getCode() === ApiConfig::HTTP_CODE_NO_CONTENT || $apiResponseTransfer->getType() === ApiOptionsTransfer::class;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param array<string, mixed> $content
     *
     * @return array<string, mixed>
     */
    protected function addValidationErrorsToResponseContent(ApiResponseTransfer $apiResponseTransfer, array $content): array
    {
        foreach ($apiResponseTransfer->getValidationErrors() as $apiValidationErrorTransfer) {
            $field = $this->formatApiValidationField($apiValidationErrorTransfer->getFieldOrFail());
            $content['errors'][$field] = $apiValidationErrorTransfer->getMessages();
        }

        return $content;
    }

    /**
     * Format '[prop1]' or '[prop1][prop2]' or '[prop1][0]' to
     * 'prop1' or 'prop1.prop2' or 'prop1.0' accordingly.
     *
     * @param string $field
     *
     * @return string
     */
    protected function formatApiValidationField(string $field): string
    {
        $field = str_replace('][', '.', $field);
        $field = trim($field, '[]');

        return $field;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer|null $apiRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getDefaultResponseHeaders(?ApiRequestTransfer $apiRequestTransfer = null): array
    {
        return [
            HttpConstants::HEADER_CONTENT_TYPE => $this->createContentTypeHeader($apiRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer|null $apiRequestTransfer
     *
     * @return string
     */
    protected function createContentTypeHeader(?ApiRequestTransfer $apiRequestTransfer = null): string
    {
        $formatType = $apiRequestTransfer && $apiRequestTransfer->getFormatType() ? $apiRequestTransfer->getFormatType() : 'json';

        return sprintf('application/%s', $formatType);
    }
}
