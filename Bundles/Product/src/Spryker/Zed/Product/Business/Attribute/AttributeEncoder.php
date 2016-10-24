<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Spryker\Zed\Product\Dependency\Facade\ProductToUtilEncodingInterface;

class AttributeEncoder implements AttributeEncoderInterface
{

    /**
     * @var ProductToUtilEncodingInterface
     */
    protected $utilEncodingFacade;

    /**
     * @param ProductToUtilEncodingInterface $utilEncodingFacade
     */
    public function __construct(ProductToUtilEncodingInterface $utilEncodingFacade)
    {
        $this->utilEncodingFacade = $utilEncodingFacade;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes)
    {
        return $this->utilEncodingFacade->encodeJson($attributes);
    }

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        $value = $this->utilEncodingFacade->decodeJson($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $value;
    }

}
