<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Dependency\Service;

interface ProductToUtilEncodingInterface
{
    /**
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null);

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array|null
     */
    public function decodeJson($jsonString, $assoc = false, $depth = null, $options = null);
}
