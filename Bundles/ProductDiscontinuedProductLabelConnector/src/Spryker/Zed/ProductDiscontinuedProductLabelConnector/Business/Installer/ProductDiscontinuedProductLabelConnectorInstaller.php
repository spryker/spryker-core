<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToGlossaryFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig;

class ProductDiscontinuedProductLabelConnectorInstaller implements ProductDiscontinuedProductLabelConnectorInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig $config
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelInterface $productLabelFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductDiscontinuedProductLabelConnectorConfig $config,
        ProductDiscontinuedProductLabelConnectorToProductLabelInterface $productLabelFacade,
        ProductDiscontinuedProductLabelConnectorToGlossaryFacadeInterface $glossaryFacade,
        ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface $localeFacade
    ) {
        $this->config = $config;
        $this->productLabelFacade = $productLabelFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            if (!$this->productLabelFacade->findLabelByLabelName($this->config->getProductDiscontinueLabelName())) {
                $productLabelTransfer = new ProductLabelTransfer();
                $productLabelTransfer->setName($this->config->getProductDiscontinueLabelName());
                $this->addDataToProductLabelTransfer($productLabelTransfer);
                $this->productLabelFacade->createLabel(
                    $productLabelTransfer
                );
            }
            $productLabelTransfer = $this->productLabelFacade->findLabelByLabelName($this->config->getProductDiscontinueLabelName());
            $this->addDataToProductLabelTransfer($productLabelTransfer);
            $this->productLabelFacade->updateLabel($productLabelTransfer);
        });
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
            ->setIsPublished(true);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $localizedAttributesTransfer = new ProductLabelLocalizedAttributesTransfer();
            $localizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $localizedAttributesTransfer->setFkProductLabel($productLabelTransfer->getIdProductLabel());
            $localizedAttributesTransfer->setLocale($localeTransfer);
            $localizedAttributesTransfer->setName(
                $this->glossaryFacade->translate($this->config->getProductDiscontinueLabelKey(), [], $localeTransfer)
            );

            $productLabelTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productLabelTransfer;
    }
}
