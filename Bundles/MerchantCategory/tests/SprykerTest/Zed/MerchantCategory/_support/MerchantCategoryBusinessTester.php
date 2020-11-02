<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategory;

use Codeception\Actor;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;

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
class MerchantCategoryBusinessTester extends Actor
{
    use _generated\MerchantCategoryBusinessTesterActions;

    /**
     * @return void
     */
    public function cleanUpDatabase(): void
    {
        $this->cleanUpMerchantCategoryTable();
    }

    /**
     * @return void
     */
    protected function cleanUpMerchantCategoryTable(): void
    {
        SpyMerchantCategoryQuery::create()->deleteAll();
    }
}
