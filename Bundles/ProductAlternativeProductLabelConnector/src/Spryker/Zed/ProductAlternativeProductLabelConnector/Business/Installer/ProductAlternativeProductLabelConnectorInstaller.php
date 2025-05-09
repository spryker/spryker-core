<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToStoreFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAlternativeProductLabelConnectorInstaller implements ProductAlternativeProductLabelConnectorInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToStoreFacadeInterface
     */
    protected ProductAlternativeProductLabelConnectorToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorConfig $config,
        ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductAlternativeProductLabelConnectorToLocaleFacadeInterface $localeFacade,
        ProductAlternativeProductLabelConnectorToStoreFacadeInterface $storeFacade
    ) {
        $this->config = $config;
        $this->productLabelFacade = $productLabelFacade;
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallTransaction(): void
    {
        if (!$this->productLabelFacade->findLabelByLabelName($this->config->getProductAlternativesLabelName())) {
            $productLabelTransfer = new ProductLabelTransfer();
            $productLabelTransfer->setName($this->config->getProductAlternativesLabelName());
            $productLabelTransfer->setFrontEndReference($this->config->getProductAlternativesLabelFrontEndReference());
            $productLabelTransfer->setPosition($this->config->getProductLabelDefaultPosition());

            $this->addDataToProductLabelTransfer($productLabelTransfer);
            $this->productLabelFacade->createLabel(
                $productLabelTransfer,
            );
        }

        $productLabelTransfer = $this->productLabelFacade->findLabelByLabelName($this->config->getProductAlternativesLabelName());

        if ($productLabelTransfer) {
            $productLabelTransfer->setFrontEndReference($this->config->getProductAlternativesLabelFrontEndReference());
            $this->addDataToProductLabelTransfer($productLabelTransfer);
            $this->productLabelFacade->updateLabel($productLabelTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function addDataToProductLabelTransfer(ProductLabelTransfer $productLabelTransfer): ProductLabelTransfer
    {
        $productLabelTransfer
            ->setIsActive(true)
            ->setIsExclusive(false)
            ->setIsDynamic(true)
            ->setIsPublished(true);

        if ($this->storeFacade->isDynamicStoreEnabled() === true) {
            return $productLabelTransfer;
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $localizedAttributesTransfer = new ProductLabelLocalizedAttributesTransfer();
            $localizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $localizedAttributesTransfer->setFkProductLabel($productLabelTransfer->getIdProductLabel());
            $localizedAttributesTransfer->setLocale($localeTransfer);
            $localizedAttributesTransfer->setName($this->config->getProductAlternativesLabelName());

            $productLabelTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productLabelTransfer;
    }
}
