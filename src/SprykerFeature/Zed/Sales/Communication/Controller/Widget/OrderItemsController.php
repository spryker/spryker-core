<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use Symfony\Component\HttpFoundation\Request;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class OrderItemsController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $viewVariables = [];

        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);
        $viewVariables['order'] = $order;

        $criteria = new \Criteria();
        $criteria->addJoin(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderItemTableMap::COL_FK_SALES_ORDER_ITEM_BUNDLE, \SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderItemBundleTableMap::COL_ID_SALES_ORDER_ITEM_BUNDLE, \Propel\Runtime\ActiveQuery\Criteria::LEFT_JOIN);
        $criteria->addAscendingOrderByColumn(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderItemBundleTableMap::COL_ID_SALES_ORDER_ITEM_BUNDLE);

        $viewVariables['items'] = $order->getItems($criteria);
        $this->addEventsToView($order);
        $this->addProductsToView($order);

        return $viewVariables;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array
     */
    protected function addProductsToView(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $productArray = [];
        foreach ($order->getItems() as $item) {
            $sku = $item->getSku();
            if (!array_key_exists($sku, $productArray)) {
                $productModel = $this->facadeCatalog->getProductBySku($sku);
                $productArray[$sku] = array('productModel' => $productModel);
            }
        }

        return $this->viewResponse([
            'products' => $productArray
        ]);
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array
     */
    protected function addEventsToView(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $groupedEvents = $this->facadeOms->getGroupedManuallyExecutableEvents($order);

        return $this->viewResponse([
            'itemEvents' => $groupedEvents['item_events'],
            'orderEvents' => $groupedEvents['order_events'],
            'uniqueItemEvents' => $groupedEvents['unique_item_events']
        ]);
    }


}
