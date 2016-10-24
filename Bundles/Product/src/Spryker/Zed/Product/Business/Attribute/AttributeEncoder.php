<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Spryker\Shared\Library\Json;

class AttributeEncoder implements AttributeEncoderInterface
{

    /**
     * @var TODO: replace static to facade
     */
    protected $utilEncodingFacade;

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes)
    {
        return Json::encode($attributes);
    }

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        $value = Json::decode($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $value;
    }

}
