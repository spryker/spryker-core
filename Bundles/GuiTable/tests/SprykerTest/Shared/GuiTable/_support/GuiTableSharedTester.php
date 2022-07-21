<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\GuiTable;

use Codeception\Actor;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilder;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class GuiTableSharedTester extends Actor
{
    use _generated\GuiTableSharedTesterActions;

    /**
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function createGuiTableConfigurationBuilder(): GuiTableConfigurationBuilderInterface
    {
        return new GuiTableConfigurationBuilder();
    }
}
