<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication;

use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Communication\Form\PriceForm;
use SprykerFeature\Zed\Price\Communication\Form\PriceTypeForm;
use SprykerFeature\Zed\Price\Communication\Grid\PriceGrid;
use SprykerFeature\Zed\Price\Communication\Grid\PriceTypeGrid;
use SprykerFeature\Zed\Price\Persistence\PriceQueryContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class PriceDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return PriceForm
     */
    public function getPriceForm(Request $request)
    {
        return new PriceForm(
            $request,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return PriceTypeForm
     */
    public function getPriceTypeForm(Request $request)
    {
        return new PriceTypeForm(
            $request,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return PriceGrid
     */
    public function getPriceGrid(Request $request)
    {
        return new PriceGrid(
            $this->getQueryContainer()->queryPriceGrid(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return PriceTypeGrid
     */
    public function getPriceTypeGrid(Request $request)
    {
        return new PriceTypeGrid(
            $this->getQueryContainer()->queryPriceTypeGrid(),
            $request
        );
    }

    /**
     * @return PriceQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->price()->queryContainer();
    }

    /**
     * @return PriceFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->price()->facade();
    }

}
