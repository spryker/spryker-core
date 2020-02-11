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
use Spryker\Zed\Api\Communication\Formatter\FormatterInterface;
use Symfony\Component\HttpFoundation\Response;

class Transformer implements TransformerInterface
{
    /**
     * @var \Spryker\Zed\Api\Communication\Formatter\FormatterInterface
     */
    protected $formatter;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @param \Spryker\Zed\Api\Communication\Formatter\FormatterInterface $formatter
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     */
    public function __construct(FormatterInterface $formatter, ApiConfig $apiConfig)
    {
        $this->formatter = $formatter;
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transform(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer, Response $response)
    {
        $defaults = [
            'Content-Type' => 'application/' . ($apiRequestTransfer->getFormatType() ?: 'json'),
        ];
        $headers = $apiResponseTransfer->getHeaders() + $defaults;

        $response->headers->add($headers);
        $response->setStatusCode($apiResponseTransfer->getCode());

        $response = $this->addResponseContent($apiRequestTransfer, $apiResponseTransfer, $response);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addResponseContent(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer, Response $response)
    {
        if ($this->isContentless($apiResponseTransfer)) {
            return $response;
        }

        $content = [];
        $content['code'] = $apiResponseTransfer->getCode();
        $content['message'] = $apiResponseTransfer->getMessage();
        if ((int)$apiResponseTransfer->getCode() === ApiConfig::HTTP_CODE_VALIDATION_ERRORS) {
            $content['errors'] = $apiResponseTransfer->getValidationErrors();
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

        $content = $this->formatter->format($content);
        $response->setContent($content);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return bool
     */
    protected function isContentless(ApiResponseTransfer $apiResponseTransfer)
    {
        return (int)$apiResponseTransfer->getCode() === ApiConfig::HTTP_CODE_NO_CONTENT || $apiResponseTransfer->getType() === ApiOptionsTransfer::class;
    }
}
