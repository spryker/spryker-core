<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorToProductInterface $productFacade,
        ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeProductLabelConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface $availabilityFacade,
        ProductAlternativeProductLabelConnectorConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->availabilityFacade = $availabilityFacade;
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
        if (!$productLabelTransfer) {
            return [];
        }

        return $this->getRelationsData($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    protected function getRelationsData(ProductLabelTransfer $productLabelTransfer): array
    {
        $idsToAssign = [];
        $idsToDeAssign = [];

        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        foreach ($this->productAlternativeFacade->findProductAbstractIdsWhichConcreteHasAlternative() as $idProductAbstract) {
            $concreteIds = $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);

            if (!$this->productAlternativeFacade->doAllConcreteProductsHaveAlternatives($concreteIds)
                || !$this->areAllConcretesUnavailableOrDiscontinued($concreteIds)
            ) {
                $idsToDeAssign[] = $idProductAbstract;

                continue;
            }

            if ($this->checkIfNeedToAddRelation($idProductLabel, $idProductAbstract, $concreteIds)) {
                $idsToAssign[] = $idProductAbstract;
            }
        }

        return [$this->mapRelationTransfer(
            $idProductLabel,
            $idsToAssign,
            $idsToDeAssign
        )];
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
     * @param int[] $concreteIds
     *
     * @return bool
     */
    protected function areAllConcretesUnavailableOrDiscontinued(array $concreteIds): bool
    {
        $isPassed = true;

        foreach ($concreteIds as $concreteId) {
            if ($this->availabilityFacade->isProductConcreteIsAvailable($concreteId)
                && !$this->productDiscontinuedFacade->isConcreteDiscontinued($concreteId)
            ) {
                $isPassed = false;
            }
        }

        return $isPassed;
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
     * @param int $idProductLabel
     * @param int[] $idsToAssign
     * @param int[] $idsToDeAssign
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer
     */
    protected function mapRelationTransfer(
        int $idProductLabel,
        array $idsToAssign,
        array $idsToDeAssign
    ): ProductLabelProductAbstractRelationsTransfer {
        $productLabelProductAbstractRelationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $productLabelProductAbstractRelationsTransfer->setIdProductLabel($idProductLabel);

        if (!empty($idsToAssign)) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToAssign($idsToAssign);
        }

        if (!empty($idsToDeAssign)) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToDeAssign($idsToDeAssign);
        }

        return $productLabelProductAbstractRelationsTransfer;
    }
}
