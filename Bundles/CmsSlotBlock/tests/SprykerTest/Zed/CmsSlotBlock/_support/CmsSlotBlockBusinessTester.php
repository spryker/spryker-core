<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlock;

use Codeception\Actor;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockBusinessFactory;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacade;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface;

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
class CmsSlotBlockBusinessTester extends Actor
{
    use _generated\CmsSlotBlockBusinessTesterActions;

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface
     */
    protected function createCmsSlotBlockFacade(): CmsSlotBlockFacadeInterface
    {
        $factory = new CmsSlotBlockBusinessFactory();
        $config = new CmsSlotBlockConfigTest();
        $factory->setConfig($config);

        $facade = new CmsSlotBlockFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
