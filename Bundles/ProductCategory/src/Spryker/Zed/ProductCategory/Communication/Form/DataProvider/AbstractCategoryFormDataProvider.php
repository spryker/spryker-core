<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormAdd;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface;

class AbstractCategoryFormDataProvider
{

    const PK_CATEGORY = 'id_category';
    const LOCALE_NAME = 'locale_name';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $currentLocale;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface $localeFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductCategoryToLocaleInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->currentLocale = $localeFacade->getCurrentLocale();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $formOptions = [
            CategoryFormAdd::OPTION_PARENT_CATEGORY_NODE_CHOICES => $this->getCategoriesWithPaths($this->currentLocale->getIdLocale()),
        ];

        return $formOptions;
    }

    /**
     * @param int $idLocale
     *
     * @return array
     */
    protected function getCategoriesWithPaths($idLocale)
    {
        $categoryEntityList = $this->categoryQueryContainer->queryCategory($idLocale)->find();

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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return string
     */
    protected function buildPath(SpyCategoryNode $node)
    {
        $pathTokens = $this->categoryQueryContainer->queryPath($node->getIdCategoryNode(), $this->currentLocale->getIdLocale(), false, true)
            ->find();

        $formattedPath = [];
        foreach ($pathTokens as $path) {
            $formattedPath[] = $path['name'];
        }

        return '/' . implode('/', $formattedPath);
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getAttributes($idCategory)
    {
        $attributeCollection = $this->categoryQueryContainer->queryAttributeByCategoryId($idCategory)
            ->innerJoinLocale()
            ->clearSelectColumns()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, CategoryFormEdit::FIELD_NAME)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_TITLE, CategoryFormEdit::FIELD_META_TITLE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_DESCRIPTION, CategoryFormEdit::FIELD_META_DESCRIPTION)
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_KEYWORDS, CategoryFormEdit::FIELD_META_KEYWORDS)
            ->withColumn(SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME, CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, self::LOCALE_NAME)
            ->find();

        $localizedAttributes = [];
        foreach ($attributeCollection as $attribute) {
            $data = $attribute->toArray();
            $localizedAttributes[$data[self::LOCALE_NAME]] = $data;
        }

        return $localizedAttributes;
    }

    /**
     * @return array
     */
    public function getAttributesDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $fields = [];
        foreach ($availableLocales as $id => $code) {
            $fields[$code] = [
                CategoryFormEdit::FIELD_NAME => null,
                CategoryFormEdit::FIELD_META_TITLE => null,
                CategoryFormEdit::FIELD_META_DESCRIPTION => null,
                CategoryFormEdit::FIELD_META_KEYWORDS => null,
                CategoryFormEdit::FIELD_CATEGORY_IMAGE_NAME => null,
            ];
        }

        return $fields;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    public function getErrorMessages(\Symfony\Component\Form\FormInterface $form)
    {
        $errors = [];

        if ($form->count() > 0) {
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $childErrors = $this->getErrorMessages($child);
                    if (!empty($childErrors)) {
                        $errors[$child->getName()] = $childErrors;
                    }
                }
            }
        } else {
            foreach ($form->getErrors(false) as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }

}
