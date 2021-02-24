<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage;

use Codeception\Actor;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;

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
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoryStorageBusinessTester extends Actor
{
    use _generated\CategoryStorageBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureCategoryTreeStorageDatabaseTableIsEmpty(): void
    {
        SpyCategoryTreeStorageQuery::create()->deleteAll();
    }
}
