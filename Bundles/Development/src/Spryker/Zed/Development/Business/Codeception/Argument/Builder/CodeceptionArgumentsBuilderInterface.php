<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Codeception\Argument\Builder;

use Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments;

interface CodeceptionArgumentsBuilderInterface
{
    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    public function build(array $options): CodeceptionArguments;
}
