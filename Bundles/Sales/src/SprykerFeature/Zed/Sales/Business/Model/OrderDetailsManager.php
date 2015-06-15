<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderDetailsManager
{
    protected $queryContainer;

    protected $facade;

    /**
     * @param SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    public function getOrderDetailsByOrderId($orderId)
    {
//        echo '<pre>';
//        var_dump($this);
//            die;

        $orderDetails = $this->queryContainer->querySalesById($orderId)->findOne();

//        var_dump($orderDetails);
//        die;


        return $orderDetails;
    }
}
