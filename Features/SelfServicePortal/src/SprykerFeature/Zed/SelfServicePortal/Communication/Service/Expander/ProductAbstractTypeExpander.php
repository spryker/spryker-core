<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductAbstractTypeExpander implements ProductAbstractTypeExpanderInterface
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface
     */
    protected $repository;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $repository
     */
    public function __construct(SelfServicePortalRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $productAbstractIds = $this->extractProductAbstractIds($cartChangeTransfer);

        if (count($productAbstractIds) === 0) {
            return $cartChangeTransfer;
        }

        $idProductAbstractToProductAbstractTypeNameMap = $this->getProductAbstractTypeMap($productAbstractIds);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getIdProductAbstract()) {
                continue;
            }

            $idProductAbstract = $itemTransfer->getIdProductAbstract();

            if (isset($idProductAbstractToProductAbstractTypeNameMap[$idProductAbstract])) {
                $itemTransfer->setProductTypes($idProductAbstractToProductAbstractTypeNameMap[$idProductAbstract]);
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<int>
     */
    protected function extractProductAbstractIds(CartChangeTransfer $cartChangeTransfer): array
    {
        $productAbstractIds = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdProductAbstract()) {
                $productAbstractIds[] = $itemTransfer->getIdProductAbstract();
            }
        }

        return array_unique($productAbstractIds);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<string>>
     */
    protected function getProductAbstractTypeMap(array $productAbstractIds): array
    {
        $productAbstractToProductAbstractTypeEntities = $this->repository->findProductAbstractTypesByProductAbstractIds($productAbstractIds);
        $idProductAbstractToProductAbstractTypeNameMap = [];

        foreach ($productAbstractToProductAbstractTypeEntities as $entity) {
            $idProductAbstract = $entity->getFkProductAbstract();
            $typeName = $entity->getProductAbstractType()->getName();

            if (!isset($idProductAbstractToProductAbstractTypeNameMap[$idProductAbstract])) {
                $idProductAbstractToProductAbstractTypeNameMap[$idProductAbstract] = [];
            }

            $idProductAbstractToProductAbstractTypeNameMap[$idProductAbstract][] = $typeName;
        }

        return $idProductAbstractToProductAbstractTypeNameMap;
    }
}
