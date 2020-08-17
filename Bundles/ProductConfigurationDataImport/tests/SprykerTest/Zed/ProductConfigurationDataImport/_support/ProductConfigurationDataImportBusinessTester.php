<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductConfigurationDataImport;

use Codeception\Actor;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;

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

    /**
     * @return void
     */
    public function cleanProductConfigurationTable(): void
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
