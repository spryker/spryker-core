<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;

class CategoryCreateDataProvider
{
    protected const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryGuiToCategoryQueryContainerInterface $queryContainer,
        CategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
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
        $categoryTransfer = (new CategoryTransfer())
            ->setIsActive(false)
            ->setIsInMenu(true)
            ->setIsClickable(true)
            ->setIsSearchable(true);

        $categoryTransfer->setCategoryNode(
            (new NodeTransfer())->setIsMain(false)
        );

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
        /** @var string[] $pathTokens */
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
