<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Sales\OrderListInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayonePaymentDetailTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\AddressTransfer;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Propel\PropelFilterCriteria;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneDetail;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemOption;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderManager
{
    /**
     * @var SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @throws PropelException
     * @throws \Exception
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        Propel::getConnection()->beginTransaction();

        try {
            $orderEntity = $this->saveOrderEntity($orderTransfer);

            // @todo: Should detect process per item, not per order
            $processName = $this->omsFacade->selectProcess($orderTransfer);
            $orderProcess = $this->omsFacade->getProcessEntity($processName);

            $this->saveOrderItems($orderTransfer, $orderEntity, $orderProcess);

            $orderTransfer->setIdSalesOrder($orderEntity->getIdSalesOrder());
            $orderTransfer->setOrderReference($orderEntity->getOrderReference());

            Propel::getConnection()->commit();
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $orderTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @throws PropelException
     *
     * @return SpySalesOrder
     */
    protected function saveOrderEntity(OrderTransfer $orderTransfer)
    {
        $orderEntity = new SpySalesOrder();

        // catch-all save; this includes an optional fk_customer
        $orderEntity->fromArray($orderTransfer->toArray());

        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($orderTransfer));

        $orderEntity->setBillingAddress($this->saveAddressTransfer($orderTransfer->getBillingAddress()));
        $orderEntity->setShippingAddress($this->saveAddressTransfer($orderTransfer->getShippingAddress()));

        $orderEntity->setGrandTotal($orderTransfer->getTotals()
            ->getGrandTotalWithDiscounts())
        ;
        $orderEntity->setSubtotal($orderTransfer->getTotals()
            ->getSubtotal())
        ;

        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($orderTransfer));

        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param $orderEntity
     * @param $orderProcess
     *
     * @throws PropelException
     */
    protected function saveOrderItems(OrderTransfer $orderTransfer, $orderEntity, $orderProcess)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $quantity = !is_null($item->getQuantity()) ? $item->getQuantity() : 1;

            $itemEntity = new SpySalesOrderItem();

            $itemEntity->fromArray($item->toArray());

            $itemEntity->setQuantity($quantity);

            $itemEntity->setFkSalesOrder($orderEntity->getIdSalesOrder());
            $itemEntity->setFkOmsOrderItemState($this->omsFacade->getInitialStateEntity()
                ->getIdOmsOrderItemState())
            ;

            $itemEntity->setProcess($orderProcess);

            $taxSet = $item->getTaxSet();
            if (null !== $taxSet) {
                $itemEntity->setTaxPercentage($taxSet->getEffectiveRate());
            }

            $itemEntity->save();

            $item->setIdSalesOrderItem($itemEntity->getIdSalesOrderItem());

            // @todo: Illegal direct dependency on ProductOption
            $this->saveProductOptions($item);
        }
    }

    /**
     * @param AddressTransfer $address
     *
     * @return SpySalesOrderAddress
     */
    protected function saveAddressTransfer(AddressTransfer $address = null)
    {
        if (is_null($address)) {
            return;
        }

        $addressEntity = new SpySalesOrderAddress();
        $addressEntity->fromArray($address->toArray());
        $addressEntity->setFkCountry($this->countryFacade->getIdCountryByIso2Code($address->getIso2Code()));

        $addressEntity->save();
        $address->setIdSalesOrderAddress($addressEntity->getIdSalesOrderAddress());

        return $addressEntity;
    }

    /**
     * @param ItemTransfer $item
     */
    protected function saveProductOptions(ItemTransfer $item)
    {
        foreach ($item->getProductOptions() as $productOption) {
            $optionEntity = new SpySalesOrderItemOption();

            $optionEntity->fromArray($productOption->toArray());

            $optionEntity->setFkSalesOrderItem($item->getIdSalesOrderItem());
            $optionEntity->setTaxPercentage($productOption->getTaxSet()
                ->getEffectiveRate());

            $optionEntity->save();
        }
    }

    /**
     * @param OrderListInterface $orderListTransfer
     *
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer)
    {
        $filter = $orderListTransfer->getFilter();
        $criteria = new Criteria();

        if (null !== $filter) {
            $criteria = (new PropelFilterCriteria($filter))
                ->toCriteria();
        }

        $ordersQuery = $this->queryContainer->querySalesOrdersByCustomerId($orderListTransfer->getIdCustomer(), $criteria)
            ->find();

        $result = [];
        foreach ($ordersQuery as $order) {
            $result[] = (new OrderTransfer())
                ->fromArray($order->toArray(), true);
        }

        $orderListTransfer->setOrders(new \ArrayObject($result));

        return $orderListTransfer;
    }

    /**
     * @param PayonePaymentDetailTransfer $paymentDetailTransfer
     * @param int $idPayment
     *
     * @return SpyPaymentPayoneDetail
     */
    public function updatePaymentDetail($paymentDetailTransfer, $idPayment)
    {
        $paymentDetailEntity = $this->queryContainer->queryPaymentDetailByPaymentId($idPayment)->findOne();
        Copy::transferToEntity($paymentDetailTransfer, $paymentDetailEntity);

        $paymentDetailEntity->save();

        return $paymentDetailEntity;
    }
}
