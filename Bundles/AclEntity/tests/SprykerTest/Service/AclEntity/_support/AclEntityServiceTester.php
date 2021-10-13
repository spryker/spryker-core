<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\AclEntity;

use Codeception\Actor;
use Spryker\Service\AclEntity\AclEntityServiceInterface;

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
class AclEntityServiceTester extends Actor
{
    use _generated\AclEntityServiceTesterActions;

    /**
     * @return \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    public function getAclEntityService(): AclEntityServiceInterface
    {
        return $this->getLocator()->aclEntity()->service();
    }
}
