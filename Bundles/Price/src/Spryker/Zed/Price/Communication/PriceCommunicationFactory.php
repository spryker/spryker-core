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
use Spryker\Zed\Price\PriceConfig;

/**
 * @method PriceConfig getConfig()
 * @method PriceQueryContainer getQueryContainer()
 */
class PriceCommunicationFactory extends AbstractCommunicationFactory
{

}
