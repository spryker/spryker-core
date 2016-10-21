<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilEncoding\Business;

/**
 * @method \Spryker\Zed\UtilEncoding\Business\UtilEncodingBusinessFactory getFactory()
 */
interface UtilEncodingFacadeInterface
{

    /**
     * Specification:
     * - Encode given value to json
     *
     * @api
     *
     * @param string $jsonValue
     * @param int $options
     * @param int $depth
     *
     * @return string
     */
    public function encodeJson($jsonValue, $options = null, $depth = null);

    /**
     * Specification:
     * - Decode given jsonValue, return array or stdObject
     *
     * @api
     *
     * @param string $jsonValue
     * @param bool $assoc
     * @param null $depth
     * @param null $options
     *
     * @return array
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null);

}
