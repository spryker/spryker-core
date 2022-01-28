<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Formatter;

use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface;

class JsonFormatter implements FormatterInterface
{
    /**
     * @var \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ApiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function format($value)
    {
        $options = Json::DEFAULT_OPTIONS | JSON_PRETTY_PRINT;

        return $this->utilEncodingService->encodeJson($value, $options) ?? '';
    }
}
