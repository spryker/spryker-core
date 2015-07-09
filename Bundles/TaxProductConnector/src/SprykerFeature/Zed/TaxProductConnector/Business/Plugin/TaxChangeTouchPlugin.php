<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxProductConnector\Business\Plugin;

use SprykerFeature\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use SprykerFeature\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use SprykerFeature\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

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
     */
    public function handleTaxRateChange($idTaxRate)
    {
        $abstractProductIds = $this->queryContainer->getAbstractProductIdsForTaxRate($idTaxRate)->find();
        foreach ($abstractProductIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }

    /**
     * @param int $idTaxSet
     */
    public function handleTaxSetChange($idTaxSet)
    {
        $abstractProductIds = $this->queryContainer->getAbstractProductIdsForTaxSet($idTaxSet)->find();
        foreach ($abstractProductIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }

}
