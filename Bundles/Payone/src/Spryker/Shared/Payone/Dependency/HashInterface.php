<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Payone\Dependency;

interface HashInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function hash($value);

}
