<?php
namespace SprykerTest\Zed\ProductCustomerPermission\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCustomerPermission
 * @group Business
 * @group Facade
 * @group ProductCustomerPermissionFacadeTest
 * Add your own group annotations below this line
 */
class ProductCustomerPermissionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCustomerPermission\ProductCustomerPermissionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveCustomerProductPermissionAddsProductPermissionToCustomer()
    {
        $product = $this->tester->haveProductAbstract();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());
    }

    /**
     * @depends testSaveCustomerProductPermissionAddsProductPermissionToCustomer
     *
     * @return void
     */
    public function testDeleteCustomerProductPermissionRemoveProductPermissionFromCustomer()
    {
        $product = $this->tester->haveProductAbstract();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testSaveCustomerProductPermissionsAddsProductPermissionsToCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);
    }

    /**
     * @depends testSaveCustomerProductPermissionsAddsProductPermissionsToCustomer
     *
     * @return void
     */
    public function testDeleteCustomerProductPermissionsRemoveProductPermissionsFromCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);
    }

    /**
     * @depends testSaveCustomerProductPermissionsAddsProductPermissionsToCustomer
     *
     * @return void
     */
    public function testDeleteAllCustomerProductPermissionsRemoveAllProductPermissionsFromCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $this->getProductCustomerPermissionFacade()
            ->deleteAllCustomerProductPermissions($customer->getIdCustomer());
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionFacadeInterface
     */
    protected function getProductCustomerPermissionFacade()
    {
        return $this->tester->getFacade();
    }
}
