<?php

namespace SprykerFeature\Yves\Cart\Tracking;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use SprykerFeature\Yves\Cart\Model\ZedCart;
use SprykerFeature\Yves\Library\Tracking\DataProvider\AbstractDataProvider;
use Silex\Application;
use SprykerEngine\Yves\Kernel\Locator;

/**
 * @property \SprykerEngine\Yves\Application\Business\Application $app
 */
class CartDataProvider extends AbstractDataProvider
{

    const DATA_PROVIDER_NAME = 'cart tracking data provider';

    /** @var Application */
    public $app;

    /** @var ZedCart */
    protected $cart;

    /** @var OrderTransfer */
    protected $orderTransfer;

    /** @var TotalsTransfer */
    protected $totalsTransfer;

    /** @var OrderItemTransferCollection */
    protected $orderItemTransferCollection;

    /** @var ExpenseTransferCollection */
    protected $expenseTransferCollection;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        $totalsTransfer = $this->findTotalsTransfer();
        $grandTotal = $totalsTransfer->getGrandTotalWithDiscounts();
        $grandTotal = $this->getDecimalValue($grandTotal);

        return $grandTotal;
    }

    /**
     * @return float
     */
    public function getShippingGrossPrice()
    {
        $shipping = 0;
        $expenseTransferCollection = $this->findOrderExpenseTransferCollection();
        foreach ($expenseTransferCollection as $expenseTransfer) {
            if ($expenseTransfer->getType() === ExpenseConstants::EXPENSE_SHIPPING) {
                $shipping += $expenseTransfer->getGrossPrice();
            }
        }
        $shipping = $this->getDecimalValue($shipping);

        return $shipping;
    }

    /**
     * @return float
     */
    public function getShippingPriceToPay()
    {
        $shipping = 0;
        $expenseTransferCollection = $this->findOrderExpenseTransferCollection();
        foreach ($expenseTransferCollection as $expenseTransfer) {
            if ($expenseTransfer->getType() === ExpenseConstants::EXPENSE_SHIPPING) {
                $shipping += $expenseTransfer->getPriceToPay();
            }
        }
        $shipping = $this->getDecimalValue($shipping);

        return $shipping;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        $totalsTransfer = $this->findTotalsTransfer();
        $taxTotal = $totalsTransfer->getTax()->getTotalAmount();
        $tax = $this->getDecimalValue($taxTotal);

        return $tax;
    }

    /**
     * @return float
     */
    public function getGrandTotalWithoutShipping()
    {
        $grandTotal = $this->getGrandTotal();
        $totalShipping = $this->getShippingPriceToPay();

        return $grandTotal - $totalShipping;
    }

    /**
     * @return float
     */
    public function getNetTotalWithoutShipping()
    {
        $netTotal = $this->getNetTotal();
        $shipping = $this->getShippingPriceToPay();

        return $netTotal - $shipping;
    }

    /**
     * @return float
     */
    public function getNetTotal()
    {
        $grandTotal = $this->getGrandTotal();
        $totalTax = $this->getTax();

        return $grandTotal - $totalTax;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        $discountAmount = $this->findTotalsTransfer()->getDiscount()->getTotalAmount();
        $shippingDiscountAmount = 0;

        $expenseTransferCollection = $this->findOrderExpenseTransferCollection();
        foreach ($expenseTransferCollection as $expenseTransfer) {
            if ($expenseTransfer->getType() === ExpenseConstants::EXPENSE_SHIPPING) {
                $shippingExpenseDiscounts = $expenseTransfer->getDiscounts();
                foreach ($shippingExpenseDiscounts as $shippingExpenseDiscount) {
                    $shippingDiscountAmount += $shippingExpenseDiscount->getAmount();
                }
            }
        }
        $discount = $this->getDecimalValue($discountAmount-$shippingDiscountAmount);

        return $discount;
    }

    /**
     * @TODO shipping tax calculation
     * @return string
     */
    public function getNetShippingCost()
    {
        return $this->getShippingPriceToPay();
    }

    /**
     * @param string $defaultValue
     * @return string
     */
    public function getPaymentMethod($defaultValue = '')
    {
        $cart = $this->findCart();
        if (!$cart) {
            return $defaultValue;
        }

        return $cart->getOrder()->getPayment()->getMethod();
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItem[]|OrderItemTransferCollection
     */
    public function findOrderItemTransferCollection()
    {
        if (!$this->orderItemTransferCollection) {
            $this->orderItemTransferCollection = $this->findOrderTransfer()->getItems();
        }

        return $this->orderItemTransferCollection;
    }

    /**
     * @return OrderTransfer
     */
    protected function findOrderTransfer()
    {
        if (!$this->orderTransfer) {
            $this->orderTransfer = $this->findCart()->getOrder();
        }

        return $this->orderTransfer;
    }

    /**
     * @return TotalsTransfer
     */
    protected function findTotalsTransfer()
    {
        if (!$this->totalsTransfer) {
            $this->totalsTransfer = $this->findCart()->getOrder()->getTotals();
        }

        return $this->totalsTransfer;
    }

    /**
     * @return \SprykerFeature\Shared\Calculation\Transfer\Expense[]|ExpenseTransferCollection
     */
    protected function findOrderExpenseTransferCollection()
    {
        if (!$this->expenseTransferCollection) {
            $this->expenseTransferCollection = $this->findOrderTransfer()->getExpenses();
        }

        return $this->expenseTransferCollection;
    }

    /**
     * @return ZedCart
     */
    protected function findCart()
    {
        if (!$this->cart) {
            $factory = Factory::getInstance();
            $cartStorage = $factory->createCartModelCartStorageZedStorage(
                ($this->app['request_stack'])? $this->app['request_stack']->getCurrentRequest() : $this->app['request'],
                $this->app->getCookieBag(),
                $this->app->getSession()
            );
            $cartSession = $factory->createCartModelCartSession($this->app->getTransferSession());
            $cartCount = $factory->createCartModelSessionCartCount($this->app->getSession());
        }

        return $this->cart;
    }

    /**
     * @param int $value
     * @return float
     */
    protected function getDecimalValue($value = 0)
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($value);
    }

}
