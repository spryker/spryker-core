<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui;

use Codeception\Actor;
use Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface;

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
class SecurityGuiBusinessTester extends Actor
{
    use _generated\SecurityGuiBusinessTesterActions;

    /**
     * @return \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface
     */
    public function getSecurityGuiFacade(): SecurityGuiFacadeInterface
    {
        return $this->getLocator()->securityGui()->facade();
    }
}
