<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper;

use Codeception\TestInterface;
use Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin;
use SprykerTest\Shared\Testify\Helper\AbstractApplicationHelper;

class ApplicationHelper extends AbstractApplicationHelper
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->addApplicationPlugin(new HttpApplicationPlugin());
    }
}
