<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Service;

class ProductCategoryFilterGuiToUtilEncodingServiceBridge implements ProductCategoryFilterGuiToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct($utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($value, $options = null, $depth = null)
    {
        return $this->utilEncodingService->encodeJson($value, $options, $depth);
    }

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array
     */
    public function decodeJson($jsonString, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingService->decodeJson($jsonString, $assoc, $depth, $options);
    }
}
