<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

class CorsFilterPostProcessor implements PostProcessorInterface
{
    public const HEADER_ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';
    public const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';
    public const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';
    public const HEADER_ORIGIN = 'origin';

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     */
    public function __construct(ApiConfig $apiConfig)
    {
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        $headers = $apiResponseTransfer->getHeaders();

        if (!$apiResponseTransfer->getOptions()) {
            $options = $apiRequestTransfer->getResourceId() ? $this->apiConfig->getHttpMethodsForItem() : $this->apiConfig->getHttpMethodsForCollection();
            $options[] = ApiConfig::HTTP_METHOD_OPTIONS;
            $apiResponseTransfer->setOptions($options);
        }

        $headers[static::HEADER_ACCESS_CONTROL_ALLOW_HEADERS] = $this->apiConfig->getAllowedRequestHeaders();
        $defaultOptions = array_merge($this->apiConfig->getAllowedRequestMethods(), [ApiConfig::HTTP_METHOD_OPTIONS]);
        $options = $apiResponseTransfer->getOptions() ?: $defaultOptions;
        $headers[static::HEADER_ACCESS_CONTROL_ALLOW_METHODS] = implode(', ', $options);

        if (!empty($headers[static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN])) {
            return $apiResponseTransfer;
        }

        $requestHeaders = $apiRequestTransfer->getHeaderData();
        $origin = isset($requestHeaders[static::HEADER_ORIGIN]) ? $requestHeaders[static::HEADER_ORIGIN] : null;
        $allowedOrigin = $origin ?: $this->apiConfig->getAllowedOrigin();

        $headers[static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN] = $allowedOrigin ?: '*';
        $apiResponseTransfer->setHeaders($headers);

        return $apiResponseTransfer;
    }
}
