<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface;

class CategoryEditDataProvider
{
    protected const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface $categoryGuiRepository
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToLocaleFacadeInterface $localeFacade,
        CategoryGuiRepositoryInterface $categoryGuiRepository
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->categoryGuiRepository = $categoryGuiRepository;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function getData(int $idCategory): ?CategoryTransfer
    {
        $categoryTransfer = $this->categoryFacade->findCategoryById($idCategory);

        if ($categoryTransfer !== null) {
            $categoryTransfer = $this->addLocalizedAttributeTransfers($categoryTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getOptions(int $idCategory): array
    {
        return [
            static::DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $this->getCategoryNodes($idCategory),
            CategoryType::OPTION_CATEGORY_TEMPLATE_CHOICES => $this->categoryGuiRepository->getIndexedCategoryTemplateNames(),
        ];
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    protected function getCategoryNodes(int $idCategory): array
    {
        $categoryNodes = [];

        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection($localeTransfer);

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {

            if ($categoryTransfer->getIdCategory() === $idCategory) {
                continue;
            }

            foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
                $categoryNodes[] = (new NodeTransfer())
                    ->setPath('/' . $nodeTransfer->getPath())
                    ->setIdCategoryNode($nodeTransfer->getIdCategoryNode())
                    ->setName($categoryTransfer->getName());
            }
        }

        return $categoryNodes;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addLocalizedAttributeTransfers(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryLocaleIds = $this->getCategoryLocaleIds($categoryTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            if (in_array($localeTransfer->getIdLocale(), $categoryLocaleIds, true)) {
                continue;
            }

            $categoryTransfer->addLocalizedAttributes(
                (new CategoryLocalizedAttributesTransfer())->setLocale($localeTransfer)
            );
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return int[]
     */
    protected function getCategoryLocaleIds(CategoryTransfer $categoryTransfer): array
    {
        $categoryLocaleIds = [];

        foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $categoryLocaleIds[] = $localizedAttribute->getLocale()->getIdLocale();
        }

        return $categoryLocaleIds;
    }
}
