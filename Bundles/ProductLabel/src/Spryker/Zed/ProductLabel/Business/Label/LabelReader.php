<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;

class LabelReader implements LabelReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface
     */
    protected $localizedAttributesCollectionReader;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface
     */
    protected $productLabelRepository;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface $localizedAttributesCollectionReader
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     */
    public function __construct(
        LocalizedAttributesCollectionReaderInterface $localizedAttributesCollectionReader,
        ProductLabelRepositoryInterface $productLabelRepository
    ) {
        $this->localizedAttributesCollectionReader = $localizedAttributesCollectionReader;
        $this->productLabelRepository = $productLabelRepository;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findByIdProductLabel($idProductLabel): ?ProductLabelTransfer
    {
        $productLabelTransfer = $this->productLabelRepository->findProductLabelById($idProductLabel);

        if (!$productLabelTransfer) {
            return null;
        }

        $this->addLocalizedAttributes($productLabelTransfer);

        return $productLabelTransfer;
    }

    /**
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByName($labelName): ?ProductLabelTransfer
    {
        $productLabelTransfer = $this->productLabelRepository->findProductLabelByName($labelName);

        if (!$productLabelTransfer) {
            return null;
        }

        $this->addLocalizedAttributes($productLabelTransfer);

        return $productLabelTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAll(): array
    {
        $productLabelTransferCollection = $this->productLabelRepository->getAllProductLabelsSortedByPosition();

        $this->addLocalizedAttributesToProductLabels($productLabelTransferCollection);

        return $productLabelTransferCollection;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllByIdProductAbstract($idProductAbstract): array
    {
        $productLabelTransferCollection = $this->productLabelRepository->getProductLabelsByIdProductAbstract($idProductAbstract);

        $this->addLocalizedAttributesToProductLabels($productLabelTransferCollection);

        return $productLabelTransferCollection;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findAllLabelIdsByIdProductAbstract($idProductAbstract): array
    {
        return $this->productLabelRepository->getProductLabelIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findAllActiveLabelIdsByIdProductAbstract($idProductAbstract): array
    {
        return $this->productLabelRepository->getActiveProductLabelIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function addLocalizedAttributes(ProductLabelTransfer $productLabelTransfer): void
    {
        $productLabelTransfer->setLocalizedAttributesCollection(
            $this
                ->localizedAttributesCollectionReader
                ->findAllByIdProductLabel($productLabelTransfer->getIdProductLabel())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection $productLabelTransferCollection
     *
     * @return void
     */
    protected function addLocalizedAttributesToProductLabels(array $productLabelTransferCollection): void
    {
        foreach ($productLabelTransferCollection as $productLabelTransfer) {
            $this->addLocalizedAttributes($productLabelTransfer);
        }
    }
}
