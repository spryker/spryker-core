<?php

namespace SprykerFeature\Zed\Sales\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SalesFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->sales()->facade();
    }

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
    public function getSalesGrid(Request $request)
    {
        return $this->getFactory()->createGridSalesGrid(
            $this->getQueryContainer()->querySales(),
            $request
        );
    }

    /**
     * @return SalesQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->sales()->queryContainer();
    }
}
