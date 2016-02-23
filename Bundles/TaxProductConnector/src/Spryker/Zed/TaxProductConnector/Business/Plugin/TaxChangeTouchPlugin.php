<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Plugin;

use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

class TaxChangeTouchPlugin implements TaxChangePluginInterface
{

    /**
     * @var \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface
     */
    private $productFacade;

    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
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
        $productAbstractIds = $this->queryContainer->getAbstractAbstractIdsForTaxRate($idTaxRate)->find();
        foreach ($productAbstractIds as $id) {
            $this->productFacade->touchProductActive((int)$id);
        }
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function handleTaxSetChange($idTaxSet)
    {
        $productAbstractIds = $this->queryContainer->getProductAbstractIdsForTaxSet($idTaxSet)->find();
        foreach ($productAbstractIds as $id) {
            $this->productFacade->touchProductActive((int)$id);
        }
    }

}
