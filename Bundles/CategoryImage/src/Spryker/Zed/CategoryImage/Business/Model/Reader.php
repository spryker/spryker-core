<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @var \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface $categoryImageTransferMapper
     * @param \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface $localeFacade
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageTransferMapperInterface $categoryImageTransferMapper,
        CategoryImageToLocaleInterface $localeFacade
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->transferMapper = $categoryImageTransferMapper;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        $categoryImageSetCollection = $this->categoryImageRepository
            ->findCategoryImageSetsByCategoryId($idCategory);

        return $this->transferMapper->mapCategoryImageSetCollection($categoryImageSetCollection);
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|null
     */
    public function findCategoryImagesSetCollectionById(int $idCategoryImageSet): ?CategoryImageSetTransfer
    {
        $categoryImageSetEntity = $this->categoryImageRepository
            ->findImageSetById($idCategoryImageSet);

        if (!$categoryImageSetEntity) {
            return null;
        }

        return $this->transferMapper->mapCategoryImageSet($categoryImageSetEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $imageSetCollection = $this->findCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );

        if (!$imageSetCollection) {
            return $categoryTransfer;
        }

        $categoryTransfer->setImageSets(new ArrayObject($imageSetCollection));

        foreach ($categoryTransfer->getImageSets() as $imageSet) {
            if ($imageSet->getLocale() === null) {
                $categoryTransfer->addImageSetDefault($imageSet->toArray());
                continue;
            }
            $localeCollection = $this->localeFacade->getLocaleCollection();
            foreach ($localeCollection as $localeTransfer) {
                if ($localeTransfer->getLocaleName() === $imageSet->getLocale()->getLocaleName()) {
                    $localeName = ucwords($localeTransfer->getLocaleName());
                    $localeName = str_replace('_', '', $localeName);
                    $categoryTransfer->{'addImageSet' . $localeName}($imageSet->toArray());
                }
            }
        }

        return $categoryTransfer;
    }
}
