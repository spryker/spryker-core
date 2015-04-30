<?php

namespace SprykerFeature\Yves\Cart\Model;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Yves\Library\Session\TransferSession;

class CartSession implements CartSessionInterface
{

    const SESSION_CART_ORDER_KEY = 'CART_SALES_ORDER';

    /**
     * @var \SprykerFeature\Shared\Sales\Transfer\Order
     * @static
     */
    protected static $cartOrder;

    /**
     * @var TransferSession
     */
    protected $session;

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param TransferSession $session
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(TransferSession $session, LocatorLocatorInterface $locator)
    {
        $this->session = $session;
        $this->locator = $locator;
    }

    /**
     * We save the cartOrder to the session when the object gets destructed
     */
    public function __destruct()
    {
        if (isset(self::$cartOrder)) {
            $this->session->set(self::SESSION_CART_ORDER_KEY, self::$cartOrder);
        }
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function getOrder()
    {
        $this->loadCartOrder();

        return self::$cartOrder;
    }

    /**
     * Clear the current cart
     */
    public function clear()
    {
        $this->session->remove(self::SESSION_CART_ORDER_KEY);
    }

    /**
     * Save a new CartOrder, if you just changed the order from get()
     * there is no need to call set().
     *
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order)
    {
        self::$cartOrder = $order;

        return $this;
    }

    /**
     * @param $sku
     * @return int
     */
    public function getQuantityBySku($sku)
    {
        $order = $this->getOrder();
        $orderItems = $order->getItems();
        $skuCount = 0;
        foreach ($orderItems as $orderItem) {
            if ($sku === $orderItem->getSku()) {
                $skuCount++;
            }
        }

        return $skuCount;
    }

    /**
     * Load order from session or create new one
     */
    protected function loadCartOrder()
    {
        if (!self::$cartOrder) {
            // TODO this must be removed
            $salesOrderTransfer = new \Generated\Shared\Transfer\SalesOrderTransfer();
            self::$cartOrder = $this->session->get(self::SESSION_CART_ORDER_KEY, $salesOrderTransfer);
        }
    }
}
