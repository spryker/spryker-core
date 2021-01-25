<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotStorage;

use Codeception\Actor;
use Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage;
use Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorageQuery;

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
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsSlotStorageBusinessTester extends Actor
{
    use _generated\CmsSlotStorageBusinessTesterActions;

    /**
     * @param string $key
     * @param array $data
     *
     * @return \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage
     */
    public function haveCmsSlotStorageInDb(string $key, array $data = []): SpyCmsSlotStorage
    {
        $cmsSlotStorageEntity = SpyCmsSlotStorageQuery::create()
            ->filterByCmsSlotKey($key)
            ->filterByKey($key)
            ->findOneOrCreate();
        $cmsSlotStorageEntity->setData($data);
        $cmsSlotStorageEntity->save();

        return $cmsSlotStorageEntity;
    }
}
