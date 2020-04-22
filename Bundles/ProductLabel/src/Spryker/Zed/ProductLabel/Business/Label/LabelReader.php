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
        return $this->productLabelRepository->findProductLabelById($idProductLabel);
    }

    /**
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByName($labelName): ?ProductLabelTransfer
    {
        return $this->productLabelRepository->findProductLabelByName($labelName);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAll(): array
    {
        return $this->productLabelRepository->getAllProductLabelsSortedByPosition();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllByIdProductAbstract($idProductAbstract): array
    {
        return $this->productLabelRepository->getProductLabelsByIdProductAbstract($idProductAbstract);
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
}
