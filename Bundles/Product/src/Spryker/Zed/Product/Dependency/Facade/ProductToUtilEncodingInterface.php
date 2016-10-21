<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Product\Dependency\Facade;

interface ProductToUtilEncodingInterface
{

    /**
     * @param string $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($value, $options = null, $depth = null);

    /**
     * @param array $jsonString
     * @param bool $assoc
     * @param null $depth
     * @param null $options
     *
     * @return array
     */
    public function decodeJson($jsonString, $assoc = false, $depth = null, $options = null);

}
