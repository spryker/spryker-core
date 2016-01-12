<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business\Plugin;

use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class TaxChangeTouchPlugin implements TaxChangePluginInterface
{

    /**
     * @var TaxProductConnectorToProductInterface
     */
    private $productFacade;

    /**
     * @var TaxProductConnectorQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var TaxProductConnectorToProductInterface
     * @var TaxProductConnectorQueryContainerInterface
     */
    public function __construct(
        TaxProductConnectorToProductInterface $productFacade,
        TaxProductConnectorQueryContainerInterface $queryContainer
    ) {
        $this->productFacade = $productFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idTaxRate
     *
     * @return void
     */
    public function handleTaxRateChange($idTaxRate)
    {
        $productAbstractIds = $this->queryContainer->getAbstractProductIdsForTaxRate($idTaxRate)->find();
        foreach ($productAbstractIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function handleTaxSetChange($idTaxSet)
    {
        $productAbstractIds = $this->queryContainer->getAbstractProductIdsForTaxSet($idTaxSet)->find();
        foreach ($productAbstractIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }

}
