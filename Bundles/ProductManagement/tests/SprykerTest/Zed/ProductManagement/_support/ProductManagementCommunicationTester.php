<?php
namespace SprykerTest\Zed\ProductManagement;

use Codeception\Actor;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyFormTypePlugin;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;

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
class ProductManagementCommunicationTester extends Actor
{
    use _generated\ProductManagementCommunicationTesterActions;

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin()
    {
        $this->setDependency(ProductManagementDependencyProvider::PLUGIN_MONEY_FORM_TYPE, function () {
            return new MoneyFormTypePlugin();
        });
    }
}
