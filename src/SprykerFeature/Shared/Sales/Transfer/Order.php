<?php

namespace SprykerFeature\Shared\Sales\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Customer\Transfer\Customer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableOptionsInterface;

class Order extends AbstractTransfer implements DiscountableContainerInterface
{

    protected $idSalesOrder = null;

    protected $fkSalesOrderAddressBilling = null;

    protected $fkSalesOrderAddressShipping = null;

    protected $fkCustomer = null;

    protected $email = null;

    protected $salutation = null;

    protected $lastName = null;

    protected $firstName = null;

    protected $incrementId = null;

    protected $billingAddress = 'Sales\\Address';

    protected $shippingAddress = 'Sales\\Address';

    protected $customer = 'Customer\\Customer';

    /**
     * @var OrderItemCollection
     */
    protected $items = 'Sales\\OrderItemCollection';

    protected $payment = '';

    protected $couponCodes = array(

    );

    protected $salesruleLog = null;

    protected $createdAt = null;

    protected $isTest = null;

    /**
     * @var TotalsInterface
     */
    protected $totals = 'Calculation\\Totals';

    /**
     * @var ExpenseItemCollectionInterface
     */
    protected $expenses = 'Calculation\\ExpenseCollection';

    /**
     * @var DiscountableItemCollectionInterface
     */
    protected $discounts = 'Calculation\\DiscountCollection';

    protected $invoice = 'Sales\\Invoice';

    /**
     * @var array
     */
    protected $enrichAbleProperties = array(
        'billingAddress' => '\\SprykerFeature\\Shared\\Sales\\Transfer\\Address',
        'shippingAddress' => '\\SprykerFeature\\Shared\\Sales\\Transfer\\Address',
        'customer' => '\\SprykerFeature\\Shared\\Customer\\Transfer\\Customer',
        'items' => '\\SprykerFeature\\Shared\\Sales\\Transfer\\OrderItemCollection',
    );

    /**
     * @param int $idSalesOrder
     * @return $this
     */
    public function setIdSalesOrder($idSalesOrder)
    {
        $this->idSalesOrder = $idSalesOrder;
        $this->addModifiedProperty('idSalesOrder');

        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesOrder()
    {
        return $this->idSalesOrder;
    }

    /**
     * @param int $fkSalesOrderAddressBilling
     *
     * @return $this
     */
    public function setFkSalesOrderAddressBilling($fkSalesOrderAddressBilling)
    {
        $this->fkSalesOrderAddressBilling = $fkSalesOrderAddressBilling;
        $this->addModifiedProperty('fkSalesOrderAddressBilling');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesOrderAddressBilling()
    {
        return $this->fkSalesOrderAddressBilling;
    }

    /**
     * @param int $fkSalesOrderAddressShipping
     *
     * @return $this
     */
    public function setFkSalesOrderAddressShipping($fkSalesOrderAddressShipping)
    {
        $this->fkSalesOrderAddressShipping = $fkSalesOrderAddressShipping;
        $this->addModifiedProperty('fkSalesOrderAddressShipping');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesOrderAddressShipping()
    {
        return $this->fkSalesOrderAddressShipping;
    }

    /**
     * @param int $fkCustomer
     *
     * @return $this
     */
    public function setFkCustomer($fkCustomer)
    {
        $this->fkCustomer = $fkCustomer;
        $this->addModifiedProperty('fkCustomer');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkCustomer()
    {
        return $this->fkCustomer;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->addModifiedProperty('email');

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $salutation
     *
     * @return $this
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        $this->addModifiedProperty('salutation');

        return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        $this->addModifiedProperty('lastName');

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        $this->addModifiedProperty('firstName');

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $incrementId
     *
     * @return $this
     */
    public function setIncrementId($incrementId)
    {
        $this->incrementId = $incrementId;
        $this->addModifiedProperty('incrementId');

        return $this;
    }

    /**
     * @return string
     */
    public function getIncrementId()
    {
        return $this->incrementId;
    }

    /**
     * @param Address $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        $this->addModifiedProperty('billingAddress');

        return $this;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        $this->addModifiedProperty('shippingAddress');

        return $this;
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param Customer $customer
     *
     * @return $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $this->addModifiedProperty('customer');

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CalculableItemCollectionInterface $items
     *
     * @return $this
     */
    public function setItems(CalculableItemCollectionInterface $items)
    {
        $this->items = $items;
        $this->addModifiedProperty('items');

        return $this;
    }

    /**
     * @return OrderItem[]|OrderItemCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem $item
     *
     * @return $this
     */
    public function addItem(OrderItem $item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * @param OrderItem $item
     *
     * @return $this
     */
    public function removeItem(OrderItem $item)
    {
        $this->items->remove($item);

        return $this;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
        $this->addModifiedProperty('payment');

        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param array|string[] $couponCodes
     *
     * @return $this
     */
    public function setCouponCodes(array $couponCodes)
    {
        $this->couponCodes = $couponCodes;
        $this->addModifiedProperty('couponCodes');

        return $this;
    }

    /**
     * @return array
     */
    public function getCouponCodes()
    {
        return $this->couponCodes;
    }

    /**
     * @param string[] $couponCode
     *
     * @return $this
     */
    public function addCouponCode($couponCode)
    {
        $this->couponCodes[] = $couponCode;

        return $this;
    }

    /**
     * @param string $salesruleLog
     *
     * @return $this
     */
    public function setSalesruleLog($salesruleLog)
    {
        $this->salesruleLog = $salesruleLog;
        $this->addModifiedProperty('salesruleLog');

        return $this;
    }

    /**
     * @return string
     */
    public function getSalesruleLog()
    {
        return $this->salesruleLog;
    }

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        $this->addModifiedProperty('createdAt');

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param bool $isTest
     *
     * @return $this
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
        $this->addModifiedProperty('isTest');

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * @param TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals(TotalsInterface $totals)
    {
        $this->totals = $totals;
        $this->addModifiedProperty('totals');

        return $this;
    }

    /**
     * @return TotalsInterface
     */
    public function getTotals()
    {
        return $this->totals;
    }

    /**
     * @param ExpenseItemCollectionInterface $expenses
     *
     * @return $this
     */
    public function setExpenses(ExpenseItemCollectionInterface $expenses)
    {
        $this->expenses = $expenses;
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @return ExpenseItemCollectionInterface|ExpenseItemInterface[]
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */
    public function addExpense(ExpenseItemInterface $expenseItem)
    {
        $this->expenses->add($expenseItem);
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @param ExpenseItemInterface $expense
     *
     * @return $this
     */
    public function removeExpense(ExpenseItemInterface $expense)
    {
        $this->expenses->remove($expense);
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @param DiscountableItemCollectionInterface $discounts
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $discounts)
    {
        $this->discounts = $discounts;
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @return Discount[]|DiscountCollection
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountItemInterface $discount)
    {
        $this->discounts->add($discount);
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function removeDiscount(DiscountItemInterface $discount)
    {
        $this->discounts->remove($discount);
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @param Invoice $invoice
     *
     * @return $this
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->addModifiedProperty('invoice');

        return $this;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return array
     */
    public function getEnrichAbleProperties()
    {
        return $this->enrichAbleProperties;
    }

    /**
     * @return DiscountableOptionsInterface[]
     */
    public function getOptions()
    {
        // TODO: Implement getOptions() method.
        return [];
    }
}
