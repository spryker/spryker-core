<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpanderInterface;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @var \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface
     */
    protected $productImageRepository;

    /**
     * @var \Spryker\Zed\ProductImage\ProductImageConfig
     */
    protected $productImageConfig;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpanderInterface
     */
    protected ?ProductImageSetExpanderInterface $productImageSetExpander;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     * @param \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface $transferMapper
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface $productImageRepository
     * @param \Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpanderInterface|null $productImageSetExpander
     */
    public function __construct(
        ProductImageQueryContainerInterface $productImageContainer,
        ProductImageTransferMapperInterface $transferMapper,
        ProductImageToLocaleInterface $localeFacade,
        ProductImageRepositoryInterface $productImageRepository,
        ?ProductImageSetExpanderInterface $productImageSetExpander = null
    ) {
        $this->productImageContainer = $productImageContainer;
        $this->transferMapper = $transferMapper;
        $this->localeFacade = $localeFacade;
        $this->productImageRepository = $productImageRepository;
        $this->productImageSetExpander = $productImageSetExpander;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet> $productImageSetCollection */
        $productImageSetCollection = $this->productImageContainer
            ->queryImageSetByProductAbstractId($idProductAbstract)
            ->find();

        return $this->transferMapper->mapProductImageSetCollection($productImageSetCollection);
    }

    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        $imageCollection = $this->productImageContainer
            ->queryImageSetByProductId($idProduct)
            ->find();

        return $this->transferMapper->mapProductImageSetCollection($imageCollection);
    }

    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();

        $imageCollection = $this->productImageContainer
            ->queryLocalizedConcreteProductImageSets($idProduct, $idLocale)
            ->find();

        if ($imageCollection->count() === 0) {
            $imageCollection = $this->productImageContainer
                ->queryDefaultConcreteProductImageSets($idProduct)
                ->find();
        }

        return $this->transferMapper->mapProductImageSetCollection($imageCollection);
    }

    /**
     * @param int $idProductImageSet
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    public function findProductImagesSetCollectionById($idProductImageSet)
    {
        $productImageSetEntity = $this->productImageContainer
            ->queryImageSetById($idProductImageSet)
            ->findOne();

        if (!$productImageSetEntity) {
            return null;
        }

        $productImageSetTransfer = $this->transferMapper->mapProductImageSet($productImageSetEntity);

        if (!$this->productImageSetExpander) {
            return $productImageSetTransfer;
        }

        return $this->productImageSetExpander
            ->expandProductImageSetCollectionWithProductImageAlternativeTextTranslations([$productImageSetTransfer])[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithImageSets(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetCollection = $this->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
        );

        if (!$imageSetCollection) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setImageSets(new ArrayObject($imageSetCollection));

        return $productAbstractTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductImage\Business\Model\Reader::expandProductConcreteTransfersWithImageSets()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithImageSets(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfersWithImageSets = $this->expandProductConcreteTransfersWithImageSets([$productConcreteTransfer]);

        return array_shift($productConcreteTransfersWithImageSets);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithImageSets(array $productConcreteTransfers): array
    {
        $productIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productIds[] = $productConcreteTransfer->getIdProductConcreteOrFail();
        }

        $productImageSetsGroupedByIdProduct = $this->productImageRepository->getProductImageSetsGroupedByIdProduct($productIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productImageSetEntities = $productImageSetsGroupedByIdProduct[$productConcreteTransfer->getIdProductConcreteOrFail()] ?? null;

            if ($productImageSetEntities !== null) {
                $productConcreteTransfer->setImageSets(
                    new ArrayObject(
                        $this->transferMapper->mapProductImageSetCollection($productImageSetEntities),
                    ),
                );
            }
        }

        return $productConcreteTransfers;
    }
}
