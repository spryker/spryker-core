<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorEntityManagerInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAlternativeProductLabelConnectorInstaller implements ProductAlternativeProductLabelConnectorInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface $productLabelFacade
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorConfig $config,
        ProductAlternativeProductLabelConnectorEntityManagerInterface $entityManager,
        ProductAlternativeProductLabelConnectorToProductLabelInterface $productLabelFacade
    )
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {

            if ($this->productLabelFacade->findLabelByLabelName($this->config->getProductAlternativesLabel())) {
                $this->productLabelFacade->updateLabel(
                    $this->productLabelFacade->findLabelByLabelName($this->config->getProductAlternativesLabel())
                );
            } else {
                $this->productLabelFacade->createLabel(
                    (new ProductLabelTransfer())
                        ->setIsActive(true)
                        ->setIsExclusive(false)
                        ->setName($this->config->getProductAlternativesLabel())
                );
            }
        });
    }
}
