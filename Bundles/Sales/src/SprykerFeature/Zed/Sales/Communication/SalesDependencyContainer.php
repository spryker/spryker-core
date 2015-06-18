<?php

namespace SprykerFeature\Zed\Sales\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Propel\Runtime\ActiveQuery\Criteria;

class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SalesFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->sales()->facade();
    }

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
        $querySales->orderByIdSalesOrder(Criteria::DESC);

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

    /**
     * @return SalesQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->sales()->queryContainer();
    }
}
