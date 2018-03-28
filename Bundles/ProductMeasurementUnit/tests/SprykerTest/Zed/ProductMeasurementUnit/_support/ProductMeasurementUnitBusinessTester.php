<?php
namespace SprykerTest\Zed\ProductMeasurementUnit;

use Codeception\Actor;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;

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

    /**
     * @param int $idProductAbstract
     * @param int $idMeasurementUnit
     *
     * @return int
     */
    public function haveProductMeasurementBaseUnit($idProductAbstract, $idMeasurementUnit)
    {
        $spyProductMeasurementBaseUnitEntity = (new SpyProductMeasurementBaseUnit())
           ->setFkProductAbstract($idProductAbstract)
           ->setFkProductMeasurementUnit($idMeasurementUnit);

        $spyProductMeasurementBaseUnitEntity->save();

        return $spyProductMeasurementBaseUnitEntity->getIdProductMeasurementBaseUnit();
    }

    /**
     * @param string $code
     *
     * @return int
     */
    public function haveProductMeasurementUnit($code)
    {
        $spyProductMeasurementUnitEntity = SpyProductMeasurementUnitQuery::create()
           ->filterByCode($code)
           ->findOneOrCreate();

        $spyProductMeasurementUnitEntity->save();

        return $spyProductMeasurementUnitEntity->getIdProductMeasurementUnit();
    }
}
