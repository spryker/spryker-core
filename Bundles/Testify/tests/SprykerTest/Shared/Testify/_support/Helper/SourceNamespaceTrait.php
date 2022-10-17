<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait SourceNamespaceTrait
{
    /**
     * @param string $namespace
     *
     * @return string
     */
    protected function removeTestSuffix(string $namespace): string
    {
        return preg_replace('#Test$#', '', $namespace);
    }
}
