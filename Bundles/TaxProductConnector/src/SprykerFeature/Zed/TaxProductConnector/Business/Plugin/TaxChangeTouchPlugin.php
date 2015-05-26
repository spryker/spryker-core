<?php

namespace SprykerFeature\Zed\TaxProductConnector\Business\Plugin;

use SprykerFeature\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use SprykerFeature\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use SprykerFeature\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class TaxChangeTouchPlugin implements TaxChangePluginInterface
{

    /**
     * @var TaxProductConnectorToProductInterface $productFacade
     */
    private $productFacade;

    /**
     * @var TaxProductConnectorQueryContainerInterface $queryContainer
     */
    private $queryContainer;

    /**
     * @var TaxProductConnectorToProductInterface $productFacade
     * @var TaxProductConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(
        TaxProductConnectorToProductInterface $productFacade,
        TaxProductConnectorQueryContainerInterface $queryContainer)
    {
        $this->productFacade = $productFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idTaxRate
     */
    public function handleTaxRateChange($idTaxRate)
    {
        $abstractProductIds = $this->queryContainer->getAbstractProductIdsForTaxRate($idTaxRate)->find()->getData();
        foreach($abstractProductIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }

    /**
     * @param int $idTaxSet
     */
    public function handleTaxSetChange($idTaxSet)
    {
        $abstractProductIds = $this->queryContainer->getAbstractProductIdsForTaxSet($idTaxSet)->find()->getData();
        foreach($abstractProductIds as $id) {
            $this->productFacade->touchProductActive((int) $id);
        }
    }
}
