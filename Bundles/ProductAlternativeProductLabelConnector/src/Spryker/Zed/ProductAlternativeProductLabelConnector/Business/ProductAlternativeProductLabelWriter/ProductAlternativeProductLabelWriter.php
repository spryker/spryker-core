<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAlternativeProductLabelWriter implements ProductAlternativeProductLabelWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\ProductConcreteDiscontinuedCheckPluginInterface[] $productConcreteDiscontinuedCheckPlugins
     */
    protected $productConcreteDiscontinuedCheckPlugins;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\ProductConcreteDiscontinuedCheckPluginInterface[] $productConcreteDiscontinuedCheckPlugins
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorToProductInterface $productFacade,
        ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade,
        array $productConcreteDiscontinuedCheckPlugins,
        ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade,
        ProductAlternativeProductLabelConnectorConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->productConcreteDiscontinuedCheckPlugins = $productConcreteDiscontinuedCheckPlugins;
        $this->availabilityFacade = $availabilityFacade;
        $this->config = $config;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithAlternativesAvailableLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $concreteIds = $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);

        if (!$this->areAllConcretesUnavailableOrDiscontinued($concreteIds)) {
            $this->productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, [$idProductAbstract]);

            return;
        }

        if ($this->checkIfNeedToAddRelation($idProductLabel, $idProductAbstract, $concreteIds)) {
            $this->productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);
        }
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     * @param array $concreteIds
     *
     * @return bool
     */
    protected function checkIfNeedToAddRelation(int $idProductLabel, int $idProductAbstract, array $concreteIds): bool
    {
        if (!in_array($idProductLabel, $this->productLabelFacade->findActiveLabelIdsByIdProductAbstract($idProductAbstract))
            && $this->areAllConcretesUnavailableOrDiscontinued($concreteIds)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param int $concreteId
     *
     * @return bool
     */
    protected function executeProductConcreteDiscontinuedCheckPlugins($concreteId): bool
    {
        foreach ($this->productConcreteDiscontinuedCheckPlugins as $productConcreteDiscontinuedCheckPlugin) {
            if (!$productConcreteDiscontinuedCheckPlugin->checkConcreteProductDiscontinued($concreteId)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int[] $concreteIds
     *
     * @return bool
     */
    protected function areAllConcretesUnavailableOrDiscontinued(array $concreteIds): bool
    {
        foreach ($concreteIds as $concreteId) {
            if ($this->availabilityFacade->isProductConcreteIsAvailable($concreteId)
                && !$this->executeProductConcreteDiscontinuedCheckPlugins($concreteId)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected function findProductAlternativeProductLabel(): ?ProductLabelTransfer
    {
        return $this->productLabelFacade->findLabelByLabelName(
            $this->config->getProductAlternativesLabelName()
        );
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $this->productLabelFacade->removeProductAbstractRelationsForLabel($productLabelTransfer->getIdProductLabel(), [$idProductAbstract]);
    }
}
