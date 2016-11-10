<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\UtilEncoding;

interface JsonInterface
{

    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     *
     * @throws \Exception
     *
     * @return string
     */
    public function encode($value, $options, $depth);

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function decode($jsonString, $assoc = false, $depth, $options);

}
