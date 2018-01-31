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
    public function testSavingProductForCustomer()
    {
        $product = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getFkProductAbstract());
    }

    /**
     * @depends testSavingProductForCustomer
     *
     * @return void
     */
    public function testRemovingProductCustomerPermission()
    {
        $product = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getFkProductAbstract());

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermission($customer->getIdCustomer(), $product->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testSavingProductsForCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $productIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $productIds[] = $this->tester->haveProduct()->getFkProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $productIds);
    }

    /**
     * @depends testSavingProductsForCustomer
     *
     * @return void
     */
    public function testRemovingProductsForCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $productIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $productIds[] = $this->tester->haveProduct()->getFkProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $productIds);

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermissions($customer->getIdCustomer(), $productIds);
    }

    /**
     * @depends testSavingProductsForCustomer
     *
     * @return void
     */
    public function testRemovingAllProductsForCustomer()
    {
        $customer = $this->tester->haveCustomer();

        $productIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $productIds[] = $this->tester->haveProduct()->getFkProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $productIds);

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
