<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Shared\Application\Helper\ZedHelper;

class PresentationHelper extends Module
{
    /**
     * @param \Codeception\TestInterface $e
     *
     * @return void
     */
    public function _before(TestInterface $e): void
    {
        $this->getZedModule()->amZed();
        $this->getZedModule()->amLoggedInUser();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Application\Helper\ZedHelper
     */
    protected function getZedModule()
    {
        return $this->getModule('\\' . ZedHelper::class);
    }
}
