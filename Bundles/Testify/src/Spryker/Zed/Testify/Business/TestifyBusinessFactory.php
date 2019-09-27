<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Testify\Business\Cleanup\OutputDirectoryCleaner;
use Spryker\Zed\Testify\Business\Cleanup\OutputDirectoryCleanerInterface;

/**
 * @method \Spryker\Zed\Testify\TestifyConfig getConfig()
 */
class TestifyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Testify\Business\Cleanup\OutputDirectoryCleanerInterface
     */
    public function createOutputCleaner(): OutputDirectoryCleanerInterface
    {
        return new OutputDirectoryCleaner($this->getConfig()->getOutputDirectoriesForCleanup());
    }
}
