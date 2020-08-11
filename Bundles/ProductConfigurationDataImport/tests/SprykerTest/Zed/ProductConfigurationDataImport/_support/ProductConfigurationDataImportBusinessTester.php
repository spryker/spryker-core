<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationDataImport;

use Codeception\Actor;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

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
 * @method \Spryker\Zed\ProductConfigurationDataImport\Business\ProductConfigurationDataImportFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationDataImportBusinessTester extends Actor
{
    use _generated\ProductConfigurationDataImportBusinessTesterActions;

    public function cleanProductConfigurationTable()
  {
      $this->getProductConfigurationQuery()->deleteAll();
  }

    /**
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    public function getProductConfigurationQuery(): SpyProductConfigurationQuery
    {
        return SpyProductConfigurationQuery::create();
    }
}
