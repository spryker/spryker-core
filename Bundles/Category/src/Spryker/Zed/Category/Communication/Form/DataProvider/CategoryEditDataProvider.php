<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\Communication\Form\CategoryType;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CategoryEditDataProvider
{
    public const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface $localeFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryFacadeInterface $categoryFacade,
        CategoryToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
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
            CategoryType::OPTION_CATEGORY_QUERY_CONTAINER => $this->queryContainer,
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
            ->queryContainer
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
        $pathTokens = $this->queryContainer->queryPath($idCategoryNode, $idLocale, false, true)
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
        return $this->queryContainer
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
