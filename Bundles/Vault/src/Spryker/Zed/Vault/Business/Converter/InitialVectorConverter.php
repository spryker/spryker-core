<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business\Converter;

class InitialVectorConverter implements InitialVectorConverterInterface
{
    /**
     * @param string $initialVector
     *
     * @return string
     */
    public function convertToHex(string $initialVector): string
    {
        return bin2hex($initialVector);
    }

    /**
     * @param string $initialVector
     *
     * @return string
     */
    public function convertToBin(string $initialVector): string
    {
        return hex2bin($initialVector);
    }
}
