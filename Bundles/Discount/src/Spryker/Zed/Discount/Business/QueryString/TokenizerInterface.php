<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

interface TokenizerInterface
{
    /**
     * @param string $queryString
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return string[]
     */
    public function tokenizeQueryString($queryString);
}
