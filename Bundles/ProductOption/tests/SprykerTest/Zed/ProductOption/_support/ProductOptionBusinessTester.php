<?php
namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery;

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
class ProductOptionBusinessTester extends Actor
{
    use _generated\ProductOptionBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     * @param int $idStore
     * @param int $idCurrency
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return void
     */
    public function addPrice(ProductOptionValueTransfer $productOptionValueTransfer, $idStore, $idCurrency, $netPrice, $grossPrice)
    {
        $productOptionValueTransfer->addPrice(
            (new MoneyValueTransfer())
                ->setFkStore($idStore)
                ->setFkCurrency($idCurrency)
                ->setNetAmount($netPrice)
                ->setGrossAmount($grossPrice)
        );
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice
     */
    public function getFirstProductOptionValueByIdProductOptionGroup($idProductOptionGroup)
    {
        return SpyProductOptionValuePriceQuery::create()
            ->joinProductOptionValue()
            ->useProductOptionValueQuery()
            ->filterByFkProductOptionGroup($idProductOptionGroup)
            ->endUse()
            ->findOne();
    }
}
