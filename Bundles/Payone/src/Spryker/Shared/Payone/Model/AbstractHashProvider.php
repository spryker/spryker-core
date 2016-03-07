<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Payone\Model;

use Spryker\Shared\Payone\Dependency\HashInterface;

abstract class AbstractHashProvider implements HashInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function hash($value)
    {
        return hash('md5', $value);
    }

}
