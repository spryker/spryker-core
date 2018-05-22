<?php
namespace SprykerTest\Zed\ProductMeasurementUnit;

use Codeception\Actor;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;

/**
 * Inherited Methods
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
class ProductMeasurementUnitBusinessTester extends Actor
{
    use _generated\ProductMeasurementUnitBusinessTesterActions;

   /**
    * Define custom actions here
    */

    const DB_TYPE_PGSQL = 'pgsql';

    /**
     * @return bool
     */
    public function isDbPgSql()
    {
        $dbType = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);

        return $dbType === static::DB_TYPE_PGSQL;
    }
}
