<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Sales\Business\Model\IdentityCrypter\Crypter;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class OrderManager
{

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param LocatorLocatorInterface $locator
     * @param FactoryInterface $factory
     */
    public function __construct(LocatorLocatorInterface $locator, FactoryInterface $factory)
    {
        $this->locator = $locator;
        $this->factory = $factory;
    }

    /**
     * @param Order $transferOrder
     * @param RequestInterface $request
     * @return ModelResult
     */
    public function saveOrder(Order $transferOrder, RequestInterface $request)
    {
        if ($transferOrder->getItems()->count() < 1) {
            return (new ModelResult())->addError('Order is empty'); // glossary
        }

        $isTest = $this->checkIfIsTest($transferOrder);
        $transferOrder->setIsTest($isTest);

        \Propel\Runtime\Propel::getConnection()->beginTransaction();

        $order = $this->factory
            ->createModelOrderBuilder($this->locator, $this->factory)
            ->createOrderEntityWithoutIncrementId($transferOrder);
        $order->setCustomerSessionId($request->getSessionId());

//        if (!$order->validate()) {
//            return (new ModelResult($order))->addError('Validation failed'); // glossary
//        }

        $this->saveOrderAndAddIncrementId($order);
//        $this->locator
//            ->salesrule()
//            ->facade()
//            ->addCodeUsage($order->getIdSalesOrder(), $transferOrder->getCouponCodes(), $order->getCustomer());

        \Propel\Runtime\Propel::getConnection()->commit();

        return new ModelResult($order);
    }

    /**
     * @param int $orderId
     * @return string
     */
    protected function generateIncrementId($orderId)
    {
        $digits = $this->factory->createSettings()->getOrderIncrementDigits();
        $keys = $this->factory->createSettings()->getOrderIncrementKeys();

        $prefix = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStorePrefix();
        $prefix .= $this->factory->createSettings()->getOrderIncrementPrefix();

        $crypter = new Crypter($keys, $digits, $prefix);

        return $crypter->encrypt($orderId);
    }

    /**
     * @fixme Can we have more than one code per order?
     * @deprecated
     * @param SpySalesOrder $salesOrder
     * @return bool
     * @throws \Exception
     */
    public function canRefundAtLeastOneCouponCode(SpySalesOrder $salesOrder)
    {
        $codeUsages = $salesOrder->getCodeUsages();

        if (count($codeUsages) < 0) {
            throw new \Exception('Could not find coupon codes for order  ' . $salesOrder->getIdSalesOrder());
        }

        foreach ($codeUsages as $codeUsage) {
            $couponCode = $codeUsage->getCode()->getCode();
            if ($this->locator->salesrule()->facade()->canRefundCouponCode($couponCode)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SpySalesOrder $order
     * @return SpySalesOrder
     */
    public function markOrderAsTestOrder(SpySalesOrder $order)
    {
        $order->setIsTest(true);
        $order->save();
        return $order;
    }

    /**
     * @param $idSalesOrder
     * @return SpySalesOrder|null
     */
    public function getOrderById($idSalesOrder)
    {
        $orderQuery = new SpySalesOrderQuery();
        return $orderQuery->findPk($idSalesOrder);
    }

    /**
     * @param Order $transferOrder
     * @return bool
     */
    protected function checkIfIsTest(Order $transferOrder)
    {
        $markAsTestConditions = $this->factory->createSettings()->getMarkAsTestConditions();
        $array = $transferOrder->toArray();
        $isTest = false;
        foreach ($markAsTestConditions as $k => $v) {
            if (isset($array[$k]) && $array[$k] === $v) {
                $isTest = true;
                break;
            }
        }
        return $isTest;
    }

    /**
     * @param $order
     */
    protected function saveOrderAndAddIncrementId($order)
    {
        $order->save();
        $incrementId = $this->generateIncrementId($order->getIdSalesOrder());
        $order->setIncrementId($incrementId);
        $order->save();
    }

}
