<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication;

use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\Price\Communication\Form\PriceForm;
use Spryker\Zed\Price\Communication\Form\PriceTypeForm;
use Spryker\Zed\Price\Communication\Grid\PriceGrid;
use Spryker\Zed\Price\Communication\Grid\PriceTypeGrid;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class PriceCommunicationFactory extends AbstractCommunicationFactory
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
