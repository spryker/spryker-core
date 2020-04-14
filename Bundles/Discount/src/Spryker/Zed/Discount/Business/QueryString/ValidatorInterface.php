<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

interface ValidatorInterface
{
    /**
     * @param string $type
     * @param string $queryString
     *
     * @return string[]
     */
    public function validateByType($type, $queryString);
}
