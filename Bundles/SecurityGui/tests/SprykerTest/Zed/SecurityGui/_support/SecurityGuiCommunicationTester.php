<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui;

use Codeception\Actor;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

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
class SecurityGuiCommunicationTester extends Actor
{
    use _generated\SecurityGuiCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|\Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory
     */
    public function getCommunicationFactory(): AbstractCommunicationFactory
    {
        $factory = $this->getFactory();

        $this->mockConfigMethod('getIgnorablePaths', function () {
            return [['bundle' => 'ignorable']];
        });

        $factory->setConfig($this->getModuleConfig('SecurityGui'));

        return $factory;
    }
}
