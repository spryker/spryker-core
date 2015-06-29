<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCommunication;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Communication\Grid\CommentsGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\OrderItemsGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\SalesGrid;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesCommunication getFactory()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getCommentForm(Request $request)
    {
        return $this->getFactory()->createFormCommentForm(
            $request,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getDemoCommentForm(Request $request)
    {
        return $this->getFactory()->createFormDemoCommentForm(
            $request,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return CommentsGrid
     */
    public function getCommentsGridByOrderId(Request $request)
    {
        return $this->getFactory()->createGridCommentsGrid(
            $this->getQueryContainer()->queryCommentsByOrderId($request->get('orderId')),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return SalesGrid
     */
    public function getSalesGrid(Request $request)
    {
        $querySales = $this->getQueryContainer()->querySales();

        return $this->getFactory()->createGridSalesGrid(
            $querySales,
            $request
        );
    }

    /**
     * @param int $orderId
     *
     * @return OrderItemsGrid
     */
    public function getOrdersItemsGridByOrderId($orderId, Request $request)
    {
        return $this->getFactory()->createGridOrderItemsGrid(
            $this->getQueryContainer()->queryOrderItems($orderId),
            $request
        );
    }

    /**
     * @return OmsFacade
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

}
