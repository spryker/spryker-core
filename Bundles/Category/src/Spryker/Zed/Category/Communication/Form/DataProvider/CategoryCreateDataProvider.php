<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Communication\Form\CategoryType;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryCreateDataProvider
{

    const DATA_CLASS = 'data_class';

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
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData()
    {
        $categoryTransfer = new CategoryTransfer();

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
        $parentCategories = $this->getCategoriesWithPaths2();
//echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($parentCategories) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        return [
            static::DATA_CLASS => CategoryTransfer::class,
            CategoryType::OPTION_PARENT_CATEGORY_NODE_CHOICES => $parentCategories,
        ];
    }

    /**
     * @return array
     */
    protected function getCategoriesWithPaths2()
    {
        $idLocale = $this->getIdLocale();
        $categoryEntityList = $this->queryContainer->queryCategory($idLocale)->find();

        $categories = [];
        $pathCache = [];

        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                if (!array_key_exists($nodeEntity->getFkParentCategoryNode(), $pathCache)) {
                    $path = $this->buildPath($nodeEntity);
                } else {
                    $path = $pathCache[$nodeEntity->getFkParentCategoryNode()];
                }

                $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)
                    ->getFirst()
                    ->getName();

//                $categories[$path][$nodeEntity->getIdCategoryNode()] = $categoryName;

                $categoryTransfer = new CategoryTransfer();
                $categoryTransfer->setPath($path);
                $categoryTransfer->setIdCategory($nodeEntity->getIdCategoryNode());
                $categoryTransfer->setName($categoryName);

                $categories[] = $categoryTransfer;
            }
        }

//        $categories = $this->sortCategoriesWithPaths($categories);

        return $categories;
    }

    /**
     * @return array
     */
    protected function getCategoriesWithPaths()
    {
        $idLocale = $this->getIdLocale();
        $categoryEntityList = $this->queryContainer->queryCategory($idLocale)->find();

        $categories = [];
        $pathCache = [];

        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                if (!array_key_exists($nodeEntity->getFkParentCategoryNode(), $pathCache)) {
                    $path = $this->buildPath($nodeEntity);
                } else {
                    $path = $pathCache[$nodeEntity->getFkParentCategoryNode()];
                }

                $categories[$path][$nodeEntity->getIdCategoryNode()] = $categoryEntity->getLocalisedAttributes($idLocale)
                    ->getFirst()
                    ->getName();
            }
        }

        $categories = $this->sortCategoriesWithPaths($categories);

        return $categories;
    }

    /**
     * @param array $categories
     *
     * @return array
     */
    protected function sortCategoriesWithPaths(array $categories)
    {
        ksort($categories);

        foreach ($categories as $path => $categoryNames) {
            asort($categories[$path], SORT_FLAG_CASE & SORT_STRING);
        }

        return $categories;
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

}
