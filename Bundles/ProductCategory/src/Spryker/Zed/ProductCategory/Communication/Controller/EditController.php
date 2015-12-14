<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;
use Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryCommunicationFactory getFactory()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class EditController extends AddController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConfig::PARAM_ID_CATEGORY);

        $currentCategory = $this->getFactory()
            ->createCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne();

        if (!$currentCategory) {
            $this->addErrorMessage(sprintf('The category you are trying to edit %s does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $locale = $this->getFactory()
            ->createCurrentLocale();

        $form = $this->getFactory()
            ->createCategoryFormEdit($idCategory);

        $form->handleRequest();

        if ($form->isValid()) {
            $connection = $this->getFactory()
                ->createPropelConnection();

            $connection->beginTransaction();

            $data = $form->getData();

            $currentCategoryTransfer = $this->updateCategory($locale, $data);
            $currentCategoryNodeTransfer = $this->updateCategoryNode($locale, $data);
            $this->updateProductCategoryMappings($currentCategoryTransfer, $data);

            $parentIdList = $data['extra_parents'];
            foreach ($parentIdList as $parentNodeId) {
                $data['fk_parent_category_node'] = $parentNodeId;
                $data['fk_category'] = $currentCategoryTransfer->getIdCategory();

                $this->updateCategoryNodeChild($currentCategoryTransfer, $locale, $data);
            }
            $this->updateProductOrder($currentCategoryTransfer, (array) json_decode($data['product_order'], true));

            $parentIdList[] = $currentCategoryNodeTransfer->getFkParentCategoryNode();
            $parentIdList = array_flip($parentIdList);
            $this->removeDeselectedCategoryAdditionalParents(
                $currentCategoryTransfer,
                $locale,
                $parentIdList
            );

            $this->addSuccessMessage('The category was saved successfully.');

            $connection->commit();

            return $this->redirectResponse('/product-category/edit?id-category=' . $idCategory);
        }

        $productCategories = $this->getFactory()
            ->createProductCategoryTable($locale, $idCategory);

        $products = $this->getFactory()
            ->createProductTable($locale, $idCategory);

        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategories->render(),
            'productsTable' => $products->render(),
            'showProducts' => true,
            'currentCategory' => $currentCategory->toArray(),
        ]);
    }

    /**
     * @param $existingCategoryNode
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    protected function createOrUpdateCategoryNode($existingCategoryNode, NodeTransfer $categoryNodeTransfer, LocaleTransfer $locale)
    {
        /* @var SpyCategoryNode $existingCategoryNode */
        if ($existingCategoryNode) {
            $categoryNodeTransfer->setIdCategoryNode($existingCategoryNode->getIdCategoryNode());

            $this->getFactory()
                ->createCategoryFacade()
                ->updateCategoryNode($categoryNodeTransfer, $locale);
        } else {
            $newData = $categoryNodeTransfer->toArray();
            unset($newData['id_category_node']);

            $categoryNodeTransfer = $this->createCategoryNodeTransferFromData($newData);

            $this->getFactory()
                ->createCategoryFacade()
                ->createCategoryNode($categoryNodeTransfer, $locale);
        }
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param LocaleTransfer $locale
     * @param array $parentIdList
     *
     * @return void
     */
    protected function removeDeselectedCategoryAdditionalParents(
        CategoryTransfer $categoryTransfer,
        LocaleTransfer $locale,
        array $parentIdList
    ) {
        $existingParents = $this->getFactory()
            ->createCategoryFacade()
            ->getNotMainNodesByIdCategory($categoryTransfer->getIdCategory());

        foreach ($existingParents as $parent) {
            if (!array_key_exists($parent->getFkParentCategoryNode(), $parentIdList)) {
                $this->getFactory()
                    ->createCategoryFacade()
                    ->deleteNode($parent->getIdCategoryNode(), $locale);
            }
        }
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param array $data
     *
     * @return void
     */
    protected function updateProductCategoryMappings(CategoryTransfer $categoryTransfer, array $data)
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
                $categoryTransfer->getIdCategory(),
                $removeProductMappingCollection
            );
        }

        if (!empty($addProductsMappingCollection)) {
            $this->getFacade()->createProductCategoryMappings(
                $categoryTransfer->getIdCategory(),
                $addProductsMappingCollection
            );
        }
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param $productOrder
     *
     * @return void
     */
    protected function updateProductOrder(CategoryTransfer $categoryTransfer, array $productOrder)
    {
        $this->getFacade()
            ->updateProductMappingsOrder($categoryTransfer->getIdCategory(), $productOrder);
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param $productPreConfig
     *
     * @return void
     */
    protected function updateProductCategoryPreconfig(CategoryTransfer $categoryTransfer, array $productPreConfig)
    {
        $this->getFacade()->updateProductCategoryPreConfig($categoryTransfer->getIdCategory(), $productPreConfig);
    }

    /**
     * @param LocaleTransfer $locale
     * @param array $data
     *
     * @return CategoryTransfer
     */
    protected function updateCategory(LocaleTransfer $locale, array $data)
    {
        $currentCategoryTransfer = $this->createCategoryTransferFromData($data);

        $this->getFactory()
            ->createCategoryFacade()
            ->updateCategory($currentCategoryTransfer, $locale);

        return $currentCategoryTransfer;
    }

    /**
     * @param LocaleTransfer $locale
     * @param array $data
     *
     * @return NodeTransfer
     */
    protected function updateCategoryNode(LocaleTransfer $locale, array $data)
    {
        $currentCategoryNodeTransfer = $this->createCategoryNodeTransferFromData($data);

        $currentCategoryNodeTransfer->setIsMain(true);

        /* @var SpyCategoryNode $currentCategoryNode */
        $existingCategoryNode = $this->getFactory()
            ->createCategoryQueryContainer()
            ->queryNodeById($currentCategoryNodeTransfer->getIdCategoryNode())
            ->findOne();

        $this->createOrUpdateCategoryNode($existingCategoryNode, $currentCategoryNodeTransfer, $locale);

        return $currentCategoryNodeTransfer;
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param LocaleTransfer $locale
     * @param array $data
     *
     * @return NodeTransfer
     */
    protected function updateCategoryNodeChild(CategoryTransfer $categoryTransfer, LocaleTransfer $locale, array $data)
    {
        $nodeTransfer = $this->createCategoryNodeTransferFromData($data);

        $nodeTransfer->setIsMain(false);

        $existingCategoryNode = $this->getFactory()
            ->createCategoryQueryContainer()
            ->queryNodeByIdCategoryAndParentNode($categoryTransfer->getIdCategory(), $nodeTransfer->getFkParentCategoryNode())
            ->findOne();

        $this->createOrUpdateCategoryNode($existingCategoryNode, $nodeTransfer, $locale);

        return $nodeTransfer;
    }

    /**
     * @param SpyCategory $category
     * @param LocaleTransfer $locale
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
     * @param SpyCategory $category
     * @param SpyCategoryNode $node
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    protected function getPathDataForView(SpyCategory $category, SpyCategoryNode $node, LocaleTransfer $locale)
    {
        $path = [];
        $pathTokens = $this->getFactory()
            ->createCategoryQueryContainer()
            ->queryPath($node->getIdCategoryNode(), $locale->getIdLocale(), true, false)
            ->find();

        $path['url'] = $this->getFactory()
            ->createCategoryFacade()
            ->generatePath($pathTokens);

        $path['view_node_name'] = 'child';
        if ((int) $category->getIdCategory() === (int) $node->getFkCategory()) {
            $path['view_node_name'] = 'parent';
        }

        return $path;
    }

    /**
     * @param SpyCategory $category
     * @param LocaleTransfer $locale
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
     * @param SpyCategory $category
     * @param SpyCategoryNode $node
     * @param LocaleTransfer $locale
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
            /* @var SpyProductCategory $productCategory */
            $productCategoryData = $productCategory->toArray();
            $productCategoryData['view_node_name'] = 'child';

            if ((int) $category->getIdCategory() === (int) $productCategory->getFkCategory()) {
                $productCategoryData['view_node_name'] = 'parent';
            }

            $productDataList[] = $productCategoryData;
        }

        return $productDataList;
    }

    /**
     * @param SpyCategory $category
     * @param LocaleTransfer $locale
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
     * @param SpyCategory $category
     * @param SpyCategoryNode $node
     *
     * @return array
     */
    protected function getBlockDataForView(SpyCategory $category, SpyCategoryNode $node)
    {
        $blockList = [];
        $blocks = $this->getFactory()
            ->createCmsFacade()
            ->getCmsBlocksByIdCategoryNode($node->getIdCategoryNode());

        foreach ($blocks as $blockTransfer) {
            $blockData = $blockTransfer->toArray();
            $blockData['view_node_name'] = 'child';
            if ((int) $category->getIdCategory() === (int) $node->getFkCategory()) {
                $blockData['view_node_name'] = 'parent';
            }

            $blockList[] = $blockData;
        }

        return $blockList;
    }

    /**
     * @param int $idCategoryNode
     * @param LocaleTransfer $locale
     *
     * @return SpyCategoryNode[]
     */
    protected function getCategoryChildren($idCategoryNode, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryQueryContainer()
            ->queryChildren($idCategoryNode, $locale->getIdLocale(), false, false)
            ->find();
    }

}
