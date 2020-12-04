<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CategoryEditDataProvider
{
    protected const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer,
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function getData(int $categoryId): ?CategoryTransfer
    {
        return $this->buildCategoryTransfer($categoryId);
    }

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function buildCategoryTransfer(int $categoryId): ?CategoryTransfer
    {
        $categoryTransfer = $this->categoryFacade->findCategoryById($categoryId);

        if ($categoryTransfer !== null) {
            $categoryTransfer = $this->addLocalizedAttributeTransfers($categoryTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    public function getOptions(int $categoryId): array
    {
        $parentCategories = $this->getCategoriesWithPaths($categoryId);

        return [
            static::DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $parentCategories,
            CategoryType::OPTION_CATEGORY_QUERY_CONTAINER => $this->categoryQueryContainer,
            CategoryType::OPTION_CATEGORY_TEMPLATE_CHOICES => $this->getCategoryTemplateChoices(),
        ];
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    protected function getCategoriesWithPaths(int $categoryId)
    {
        $idLocale = $this->getIdLocale();
        /** @var \Orm\Zed\Category\Persistence\SpyCategory[] $categoryEntityList */
        $categoryEntityList = $this
            ->categoryQueryContainer
            ->queryCategory($idLocale)
            ->useNodeQuery()
                ->orderByNodeOrder(Criteria::DESC)
            ->endUse()
            ->find();

        $categoryNodes = [];

        foreach ($categoryEntityList as $categoryEntity) {
            if ($categoryEntity->getIdCategory() === $categoryId) {
                continue;
            }

            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                $path = $this->buildPath($nodeEntity);
                $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();

                $categoryNodeTransfer = new NodeTransfer();
                $categoryNodeTransfer->setPath($path);
                $categoryNodeTransfer->setIdCategoryNode($nodeEntity->getIdCategoryNode());
                $categoryNodeTransfer->setName($categoryName);

                $categoryNodes[] = $categoryNodeTransfer;
            }
        }

        return $categoryNodes;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return string
     */
    protected function buildPath(SpyCategoryNode $categoryNodeEntity)
    {
        $idLocale = $this->getIdLocale();
        $idCategoryNode = $categoryNodeEntity->getIdCategoryNode();
        /** @var string[] $pathTokens */
        $pathTokens = $this->categoryQueryContainer->queryPath($idCategoryNode, $idLocale, false, true)
            ->clearSelectColumns()->addSelectColumn('name')
            ->find();

        return '/' . implode('/', $pathTokens);
    }

    /**
     * @return int
     */
    protected function getIdLocale()
    {
        return $this->localeFacade->getCurrentLocale()->getIdLocale();
    }

    /**
     * @return array
     */
    protected function getCategoryTemplateChoices()
    {
        return $this->categoryQueryContainer
            ->queryCategoryTemplate()
            ->find()
            ->toKeyValue('idCategoryTemplate', 'name');
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
            if (in_array($localeTransfer->getIdLocale(), $categoryLocaleIds)) {
                continue;
            }

            $categoryLocalizedAttributesTransfer = $this->createEmptyCategoryLocalizedAttributesTransfer($localeTransfer);
            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function createEmptyCategoryLocalizedAttributesTransfer(LocaleTransfer $localeTransfer): CategoryLocalizedAttributesTransfer
    {
        return (new CategoryLocalizedAttributesTransfer())->setLocale($localeTransfer);
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
