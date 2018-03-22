<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Formatter;

use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class JsonFormatter implements FormatterInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $service
     */
    public function __construct(UtilEncodingServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function format($value)
    {
        $options = Json::DEFAULT_OPTIONS | JSON_PRETTY_PRINT;

        return $this->service->encodeJson($value, $options);
    }
}
