<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorRepositoryInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface $productLabelFacade
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorRepositoryInterface $productAlternativeProductLabelConnectorRepository
     */
    protected $productAlternativeProductLabelConnectorRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorRepositoryInterface $productAlternativeProductLabelConnectorRepository
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorToProductInterface $productFacade,
        ProductAlternativeProductLabelConnectorToProductLabelInterface $productLabelFacade,
        ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeProductLabelConnectorRepositoryInterface $productAlternativeProductLabelConnectorRepository,
        ProductAlternativeProductLabelConnectorConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->productAlternativeProductLabelConnectorRepository = $productAlternativeProductLabelConnectorRepository;
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        if (!$this->productAlternativeProductLabelConnectorRepository
            ->getIsProductLabelActive($this->config->getProductAlternativesLabelName())
        ) {
            return [];
        }

        $productIds = $this->productAlternativeProductLabelConnectorRepository->getProductConcreteIds();

        $idsToAssign = [];
        $idsToDeAssign = [];

        $idProductLabel = $this->productLabelFacade->findLabelByLabelName(
            $this->config->getProductAlternativesLabelName()
        )->getIdProductLabel();

        foreach ($productIds as $idProduct) {
            $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteId($idProduct);
            $concreteIds = $this->getProductConcreteIdsByAbstractProductId($idProductAbstract);

            if (!$this->productAlternativeFacade->doAllConcreteProductsHaveAlternatives($concreteIds)) {
                $idsToDeAssign[] = $idProductAbstract;

                continue;
            }

            if (!in_array($idProductLabel, $this->productLabelFacade->findActiveLabelIdsByIdProductAbstract($idProductAbstract))
                && !in_array($idProductAbstract, $idsToAssign)
            ) {
                $idsToAssign[] = $idProductAbstract;
            }
        }

        return [$this->mapRelationTransfer($idProductLabel, $idsToAssign, $idsToDeAssign)];
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        $concreteIds = [];

        foreach ($this->productFacade->getConcreteProductsByAbstractProductId($idProductAbstract) as $productConcreteTransfer) {
            $concreteIds[] = $productConcreteTransfer->getIdProductConcrete();
        }

        return $concreteIds;
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
