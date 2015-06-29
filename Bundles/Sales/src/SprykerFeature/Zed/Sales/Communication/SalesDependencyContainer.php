<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Sales\Communication\Grid\CommentsGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\OrderItemsGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\SalesGrid;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesCommunication getFactory()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return mixed
     */
    public function getCommentForm()
    {
        return $this->getFactory()->createFormCommentForm(
            null,
            $this->getQueryContainer()
        );
    }

    /**
     * @return mixed
     */
    public function getDemoCommentForm()
    {
        return $this->getFactory()->createFormDemoCommentForm(
            null,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     * @return CommentsGrid
     */
    public function getCommentsGridByOrderId(Request $request)
    {
        return $this->getFactory()->createGridCommentsGrid(
            $this->getQueryContainer()->queryCommentsByOrderId($request->get('orderId'))
        );
    }

    /**
     * @return SalesGrid
     */
    public function getSalesGrid()
    {
        $salesQuery = $this->getQueryContainer()->querySales();
        return $this->getFactory()->createGridSalesGrid($salesQuery);
    }

    /**
     * @param int $idOrder
     *
     * @return OrderItemsGrid
     */
    public function getOrdersItemsGridByOrderId($idOrder)
    {
        return $this->getFactory()->createGridOrderItemsGrid(
            $this->getQueryContainer()->queryOrderItems($idOrder));
    }


}
