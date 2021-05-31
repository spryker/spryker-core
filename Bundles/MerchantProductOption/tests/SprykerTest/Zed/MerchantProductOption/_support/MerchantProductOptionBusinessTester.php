<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOption;

use Codeception\Actor;
use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;

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
class MerchantProductOptionBusinessTester extends Actor
{
    use _generated\MerchantProductOptionBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantProductOptionGroupTableEmpty(): void
    {
        $this->createMerchantProductOptionGroupPropelQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery
     */
    public function createMerchantProductOptionGroupPropelQuery(): SpyMerchantProductOptionGroupQuery
    {
        return SpyMerchantProductOptionGroupQuery::create();
    }
}
