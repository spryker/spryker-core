<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\Model;

interface JsonInterface
{
    /**
     * @param mixed $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @throws \Exception
     *
     * @return string|null
     */
    public function encode($value, $options = null, $depth = null);

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @throws \Exception
     *
     * @return mixed|null
     */
    public function decode($jsonValue, $assoc = false, $depth = null, $options = null);
}
