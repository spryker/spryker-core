<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsDataImportCommunicationTester extends Actor
{
    use _generated\MerchantOmsDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantOmsProcessTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOmsProcessPropelQuery());
    }

    /**
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    protected function getMerchantOmsProcessPropelQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }
}
