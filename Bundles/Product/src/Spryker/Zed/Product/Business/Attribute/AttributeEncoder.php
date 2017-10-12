<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface;

class AttributeEncoder implements AttributeEncoderInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(ProductToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes)
    {
        return $this->utilEncodingService->encodeJson($attributes);
    }

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        $value = $this->utilEncodingService->decodeJson($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $value;
    }
}
