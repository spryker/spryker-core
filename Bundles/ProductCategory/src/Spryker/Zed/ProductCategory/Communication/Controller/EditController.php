<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Shared\ProductCategory\ProductCategoryConstants;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacade getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer getQueryContainer()
 */
class EditController extends AddController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(ProductCategoryConstants::PARAM_ID_CATEGORY));

        $currentCategory = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne();

        if (!$currentCategory) {
            $this->addErrorMessage(sprintf('The category you are trying to edit %s does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $localeTransfer = $this->getFactory()->getCurrentLocale();

        $dataProvider = $this->getFactory()->createCategoryFormEditDataProvider();
        $form = $this
            ->getFactory()
            ->createCategoryFormEdit(
                $dataProvider->getData($idCategory),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            if ($this->updateCategoryData($form->getData())) {
                $this->addSuccessMessage('The category was saved successfully.');

                return $this->redirectResponse('/product-category/edit?id-category=' . $idCategory);
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorMessage('Please make sure required fields are properly filled in');
        }

        $productCategories = $this->getFactory()
            ->createProductCategoryTable($localeTransfer, $idCategory);

        $products = $this->getFactory()
            ->createProductTable($localeTransfer, $idCategory);

        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategories->render(),
            'productsTable' => $products->render(),
            'showProducts' => true,
            'currentCategory' => $currentCategory->toArray(),
            'currentLocale' => $this->getFactory()->getCurrentLocale()->getLocaleName(),
            'errors' => $dataProvider->getErrorMessages($form)
        ]);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $existingCategoryNode
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function createOrUpdateCategoryNode($existingCategoryNode, NodeTransfer $nodeTransfer, LocaleTransfer $locale)
    {
        /* @var \Orm\Zed\Category\Persistence\SpyCategoryNode $existingCategoryNode */
        if ($existingCategoryNode) {
            $nodeTransfer->setIdCategoryNode($existingCategoryNode->getIdCategoryNode());

            $this->getFactory()
                ->getCategoryFacade()
                ->updateCategoryNode($nodeTransfer, $locale);
        } else {
            $newData = $nodeTransfer->toArray();
            unset($newData['id_category_node']);

            $nodeTransfer = (new NodeTransfer())
                ->fromArray($newData);

            $this->getFactory()
                ->getCategoryFacade()
                ->createCategoryNode($nodeTransfer, $locale);
        }
    }

    /**
     * @param int $idCategory
     * @param array $localeCollection
     * @param array $parentIdList
     *
     * @return void
     */
    protected function removeDeselectedCategoryAdditionalParents(
        $idCategory,
        array $localeCollection,
        array $parentIdList
    ) {
        return;

        $existingParents = $this->getFactory()
            ->getCategoryFacade()
            ->getNotMainNodesByIdCategory($idCategory);

        foreach ($existingParents as $parent) {
            if (!array_key_exists($parent->getFkParentCategoryNode(), $parentIdList)) {
                foreach ($localeCollection as $locale) {
                    $this->getFactory()
                        ->getCategoryFacade()
                        ->deleteNode($parent->getIdCategoryNode(), $locale);
                }
            }
        }
    }

    /**
     * @param int $idCategory
     * @param array $data
     *
     * @return void
     */
    protected function updateProductCategoryMappings($idCategory, array $data)
    {
        $addProductsMappingCollection = [];
        $removeProductMappingCollection = [];
        if (trim($data['products_to_be_assigned']) !== '') {
            $addProductsMappingCollection = explode(',', $data['products_to_be_assigned']);
        }

        if (trim($data['products_to_be_de_assigned']) !== '') {
            $removeProductMappingCollection = explode(',', $data['products_to_be_de_assigned']);
        }

        if (!empty($removeProductMappingCollection)) {
            $this->getFacade()->removeProductCategoryMappings(
                $idCategory,
                $removeProductMappingCollection
            );
        }

        if (!empty($addProductsMappingCollection)) {
            $this->getFacade()->createProductCategoryMappings(
                $idCategory,
                $addProductsMappingCollection
            );
        }
    }

    /**
     * @param int $idCategory
     * @param array $productOrder
     *
     * @return void
     */
    protected function updateProductOrder($idCategory, array $productOrder)
    {
        $this->getFacade()
            ->updateProductMappingsOrder($idCategory, $productOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array $productPreConfig
     *
     * @return void
     */
    protected function updateProductCategoryPreconfig(CategoryTransfer $categoryTransfer, array $productPreConfig)
    {
        $this->getFacade()->updateProductCategoryPreConfig($categoryTransfer->getIdCategory(), $productPreConfig);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function updateCategoryData(array $data)
    {
        $this->getFactory()->getPropelConnection()->beginTransaction();

        $categoryKey = $data['category_key'];
        $idCategory = $data['id_category'];

        $entity = $this->getFactory()->getCategoryQueryContainer()
            ->queryCategoryByKey($categoryKey)
            ->findOne();

        if ($entity) {
            if ((int) $entity->getIdCategory() !== (int) $idCategory) {
                $this->addErrorMessage(sprintf(
                    'Category with key "%s" already exists',
                    $categoryKey
                ));

                return false;
            }
        }

        $locales = [];
        $nodeTransfer = new NodeTransfer();
        $attributes = $data[CategoryFormEdit::LOCALIZED_ATTRIBUTES];

        foreach ($attributes as $localeCode => $localizedAttributes) {
            $localeTransfer = $this->getFactory()->getLocaleFacade()->getLocale($localeCode);
            $locales[] = $localeTransfer;

            $data['is_main'] = true;
            $categoryData = array_merge($attributes[$localeCode], $data);
            $this->updateCategory($localeTransfer, $categoryData);

            $nodeTransfer = $this->updateCategoryNode($localeTransfer, $data);
        }

        $this->updateProductCategoryMappings($idCategory, $data);

        $parentIdList = $data['extra_parents'];
        foreach ($parentIdList as $parentNodeId) {
            $this->updateExtraParents($nodeTransfer, $locales, $parentNodeId);
        }

        $this->updateProductOrder($idCategory, (array)json_decode($data['product_order'], true));

        $parentIdList[] = $data['fk_parent_category_node'];
        $parentIdList = array_flip($parentIdList);
        $this->removeDeselectedCategoryAdditionalParents(
            $idCategory,
            $locales,
            $parentIdList
        );

        $this->getFactory()->getPropelConnection()->commit();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function updateCategory(LocaleTransfer $locale, array $data)
    {
        $currentCategoryTransfer = $this->createCategoryTransferFromData($data);

        $this->getFactory()
            ->getCategoryFacade()
            ->updateCategory($currentCategoryTransfer, $locale);

        return $currentCategoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function updateCategoryNode(LocaleTransfer $locale, array $data)
    {
        $nodeTransfer = $this->createCategoryNodeTransferFromData($data);

        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNode $currentCategoryNode */
        $existingCategoryNode = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryNodeById($nodeTransfer->requireIdCategoryNode()->getIdCategoryNode())
            ->findOne();

        $this->createOrUpdateCategoryNode($existingCategoryNode, $nodeTransfer, $locale);

        return $nodeTransfer;
    }


    protected function updateExtraParents(NodeTransfer $mainNodeTransfer, array $localeCollection, $extraParentNodeId)
    {
        if ((int) $extraParentNodeId === (int) $mainNodeTransfer->getIdCategoryNode()) {
            return;
        }

        $nodeTransfer = (new NodeTransfer())
            ->setFkCategory($mainNodeTransfer->getFkCategory())
            ->setFkParentCategoryNode($extraParentNodeId)
            ->setIsMain(false)
            ->setIsRoot(false);

        $existingCategoryNode = null;
        foreach ($localeCollection as $localeTransfer) {
            if ($existingCategoryNode === null) {
                $existingCategoryNode = $this->getFactory()
                    ->getCategoryQueryContainer()
                    ->queryNodeByIdCategoryAndParentNode($mainNodeTransfer->getFkCategory(), $extraParentNodeId)
                    ->filterByIsMain(false)
                    ->findOne();
            }

            if ($existingCategoryNode && ((int)$existingCategoryNode->getIdCategoryNode() !== (int)$mainNodeTransfer->getIdCategoryNode())) {
                $nodeTransfer->setIdCategoryNode($existingCategoryNode->getIdCategoryNode());

                $this->getFactory()
                    ->getCategoryFacade()
                    ->updateCategoryNode($nodeTransfer, $localeTransfer);

                continue;
            }

            if (!$existingCategoryNode) {
                $this->getFactory()
                    ->getCategoryFacade()
                    ->createCategoryNode($nodeTransfer, $localeTransfer);
            }
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function getPaths(SpyCategory $category, LocaleTransfer $locale)
    {
        $paths = [];
        foreach ($category->getNodes() as $node) {
            $children = $this->getCategoryChildren($node->getIdCategoryNode(), $locale);

            foreach ($children as $child) {
                $paths[] = $this->getPathDataForView($category, $child, $locale);
            }
        }

        return $paths;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function getPathDataForView(SpyCategory $category, SpyCategoryNode $node, LocaleTransfer $locale)
    {
        $path = [];
        $pathTokens = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryPath($node->getIdCategoryNode(), $locale->getIdLocale(), true, false)
            ->find();

        $path['url'] = $this->getFactory()
            ->getCategoryFacade()
            ->generatePath($pathTokens);

        $path['view_node_name'] = 'child';
        if ((int)$category->getIdCategory() === (int)$node->getFkCategory()) {
            $path['view_node_name'] = 'parent';
        }

        return $path;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function getProducts(SpyCategory $category, LocaleTransfer $locale)
    {
        $productList = [];
        foreach ($category->getNodes() as $node) {
            $children = $this->getCategoryChildren($node->getIdCategoryNode(), $locale);

            foreach ($children as $child) {
                if (isset($productList[$child->getFkCategory()])) {
                    continue;
                }

                $productDataList = $this->getProductDataForView($category, $child, $locale);
                $productList = array_merge($productList, $productDataList);
            }
        }

        return $productList;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function getProductDataForView(SpyCategory $category, SpyCategoryNode $node, LocaleTransfer $locale)
    {
        $productCategoryList = $this->getQueryContainer()
            ->queryProductsByCategoryId($node->getFkCategory(), $locale)
            ->find();

        $productDataList = [];
        foreach ($productCategoryList as $productCategory) {
            /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategory */
            $productCategoryData = $productCategory->toArray();
            $productCategoryData['view_node_name'] = 'child';

            if ((int)$category->getIdCategory() === (int)$productCategory->getFkCategory()) {
                $productCategoryData['view_node_name'] = 'parent';
            }

            $productDataList[] = $productCategoryData;
        }

        return $productDataList;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function getBlocks(SpyCategory $category, LocaleTransfer $locale)
    {
        $blockList = [];
        foreach ($category->getNodes() as $node) {
            $children = $this->getCategoryChildren($node->getIdCategoryNode(), $locale);

            foreach ($children as $child) {
                $childBlockList = $this->getBlockDataForView($category, $child);
                $blockList = array_merge($childBlockList, $blockList);
            }
        }

        return $blockList;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $category
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return array
     */
    protected function getBlockDataForView(SpyCategory $category, SpyCategoryNode $node)
    {
        $blockList = [];
        $blocks = $this->getFactory()
            ->getCmsFacade()
            ->getCmsBlocksByIdCategoryNode($node->getIdCategoryNode());

        foreach ($blocks as $blockTransfer) {
            $blockData = $blockTransfer->toArray();
            $blockData['view_node_name'] = 'child';
            if ((int)$category->getIdCategory() === (int)$node->getFkCategory()) {
                $blockData['view_node_name'] = 'parent';
            }

            $blockList[] = $blockData;
        }

        return $blockList;
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    protected function getCategoryChildren($idCategoryNode, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryChildren($idCategoryNode, $locale->getIdLocale(), false, false)
            ->find();
    }

}
