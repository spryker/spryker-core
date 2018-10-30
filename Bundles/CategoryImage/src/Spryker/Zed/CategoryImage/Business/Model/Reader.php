<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface $localeProvider
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        LocaleProviderInterface $localeProvider
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryImageSetCollection = $this->findCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );

        if ($categoryImageSetCollection) {
            $localizedCategoryImageSetCollection = $this->buildLocalizedImageSetCollection($categoryImageSetCollection);
            $categoryTransfer->setFormImageSets($localizedCategoryImageSetCollection);
        }

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        return $this->categoryImageRepository->findCategoryImageSetsByCategoryId($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    protected function buildLocalizedImageSetCollection(array $categoryImageSetCollection): array
    {
        $localizedImageSetCollection = [];

        foreach ($this->localeProvider->getLocaleCollection(true) as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $localizedImageSetCollection[$localeName] = array_filter(
                $categoryImageSetCollection,
                function (CategoryImageSetTransfer $categoryImageSet) use ($localeName) {
                    $this->prepareLocale($categoryImageSet);

                    return $categoryImageSet->getLocale()->getLocaleName() === $localeName;
                }
            );
        }

        return $localizedImageSetCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function prepareLocale(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        if ($categoryImageSetTransfer->getLocale() === null) {
            $categoryImageSetTransfer->setLocale(
                $this->localeProvider->createDefaultLocale()
            );
        }

        return $categoryImageSetTransfer;
    }
}
