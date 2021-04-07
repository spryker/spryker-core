<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface;

class CategoryCreateDataProvider
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface $categoryGuiRepository
     */
    public function __construct(
        CategoryGuiToLocaleFacadeInterface $localeFacade,
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiRepositoryInterface $categoryGuiRepository
    ) {
        $this->localeFacade = $localeFacade;
        $this->categoryFacade = $categoryFacade;
        $this->categoryGuiRepository = $categoryGuiRepository;
    }

    /**
     * @param int|null $idParentNode
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData(?int $idParentNode): CategoryTransfer
    {
        $categoryTransfer = (new CategoryTransfer())
            ->setIsActive(false)
            ->setIsInMenu(true)
            ->setIsClickable(true)
            ->setIsSearchable(true)
            ->setStoreRelation(new StoreRelationTransfer());

        $categoryTransfer->setCategoryNode(
            (new NodeTransfer())->setIsMain(false)
        );

        if ($idParentNode) {
            $categoryTransfer->setParentCategoryNode(
                (new NodeTransfer())->setIdCategoryNode($idParentNode)
            );
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localTransfer) {
            $categoryTransfer->addLocalizedAttributes(
                (new CategoryLocalizedAttributesTransfer())->setLocale($localTransfer)
            );
        }

        return $categoryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CategoryType::OPTION_DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $this->getCategoryNodes(),
            CategoryType::OPTION_CATEGORY_TEMPLATE_CHOICES => $this->categoryGuiRepository->getIndexedCategoryTemplateNames(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    protected function getCategoryNodes(): array
    {
        $nodeTransfers = [];

        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection($localeTransfer);

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $nodeTransfers = $this->extractNodesFromCategory($nodeTransfers, $categoryTransfer);
        }

        return $nodeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    protected function extractNodesFromCategory(array $nodeTransfers, CategoryTransfer $categoryTransfer): array
    {
        foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
            $nodeTransfers[] = (new NodeTransfer())
                ->setPath('/' . $nodeTransfer->getPath())
                ->setIdCategoryNode($nodeTransfer->getIdCategoryNode())
                ->setName($categoryTransfer->getName());
        }

        return $nodeTransfers;
    }
}
