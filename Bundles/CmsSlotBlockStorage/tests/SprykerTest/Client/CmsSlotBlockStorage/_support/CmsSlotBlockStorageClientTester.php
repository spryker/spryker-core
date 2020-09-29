<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CmsSlotBlockStorage;

use Codeception\Actor;
use Spryker\Client\CmsSlotBlockStorage\CmsSlotBlockStorageClientInterface;

/**
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
class CmsSlotBlockStorageClientTester extends Actor
{
    use _generated\CmsSlotBlockStorageClientTesterActions;

    /**
     * @return \Spryker\Client\CmsSlotBlockStorage\CmsSlotBlockStorageClientInterface
     */
    public function getCmsSlotBlockStorageClient(): CmsSlotBlockStorageClientInterface
    {
        return $this->getLocator()
            ->cmsSlotBlockStorage()
            ->client();
    }
}
