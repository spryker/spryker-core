<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Communication\Form\CategoryType;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryCreateDataProvider
{
    public const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface $localeFacade
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer, CategoryToLocaleInterface $localeFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idParentNode
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData($idParentNode)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setIsActive(false);
        $categoryTransfer->setIsInMenu(true);
        $categoryTransfer->setIsClickable(true);
        $categoryTransfer->setIsSearchable(true);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setIsMain(false);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        if ($idParentNode) {
            $parentCategoryNodeTransfer = new NodeTransfer();
            $parentCategoryNodeTransfer->setIdCategoryNode($idParentNode);
            $categoryTransfer->setParentCategoryNode($parentCategoryNodeTransfer);
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localTransfer) {
            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->setLocale($localTransfer);
            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $parentCategories = $this->getCategoriesWithPaths();

        return [
            static::DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $parentCategories,
            CategoryType::OPTION_CATEGORY_QUERY_CONTAINER => $this->queryContainer,
            CategoryType::OPTION_CATEGORY_TEMPLATE_CHOICES => $this->getCategoryTemplateChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getCategoriesWithPaths()
    {
        $idLocale = $this->getIdLocale();
        $categoryEntityList = $this->queryContainer->queryCategory($idLocale)->find();

        $categoryNodes = [];

        foreach ($categoryEntityList as $categoryEntity) {
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
}
