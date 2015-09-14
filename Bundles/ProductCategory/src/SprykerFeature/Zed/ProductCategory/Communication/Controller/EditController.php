<?php

namespace SprykerFeature\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
use SprykerFeature\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryDependencyContainer getDependencyContainer()
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

        $categoryExists = $this->getDependencyContainer()
                ->createCategoryQueryContainer()
                ->queryCategoryById($idCategory)->count() > 0;

        if (!$categoryExists) {
            $this->addErrorMessage(sprintf('The category you are trying to edit %s does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $locale = $this->getDependencyContainer()
            ->createCurrentLocale();

        /**
         * @var Form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryFormEdit($idCategory);

        $form->handleRequest();

        if ($form->isValid()) { //TODO Ugly and dirty, some stuff must be moved into Facades
            $data = $form->getData();

            $categoryTransfer = (new CategoryTransfer())
                ->fromArray($data, true);

            $this->getDependencyContainer()
                ->createCategoryFacade()
                ->updateCategory($categoryTransfer, $locale);

            $parentIdList = $form->getData()['fk_parent_category_node'];
            foreach ($parentIdList as $parentNodeId) {
                $data['fk_parent_category_node'] = $parentNodeId;
                $data['fk_category'] = $categoryTransfer->getIdCategory();

                $categoryNodeTransfer = (new NodeTransfer())
                    ->fromArray($data, true);

                $existingCategoryNode = $this->getDependencyContainer()
                    ->createCategoryFacade()
                    ->getNodeByIdCategoryAndParentNode($categoryTransfer->getIdCategory(), $parentNodeId);

                $this->createOrUpdateCategoryNode($existingCategoryNode, $categoryNodeTransfer, $locale);
            }

            $parentIdList = array_flip($parentIdList);
            $addProductsMappingCollection = [];
            $removeProductMappingCollection = [];
            if (trim($data['products_to_be_assigned']) !== '') {
                $addProductsMappingCollection = explode(',', $data['products_to_be_assigned']);
            }

            if (trim($data['products_to_be_de_assigned']) !== '') {
                $removeProductMappingCollection = explode(',', $data['products_to_be_de_assigned']);
            }

            $this->updateCategoryParents(
                $categoryTransfer, 
                $locale, 
                $parentIdList, 
                $addProductsMappingCollection, 
                $removeProductMappingCollection
            );

            $this->addSuccessMessage('The category was saved successfully.');

            return $this->redirectResponse('/category');
        }

        $productCategories = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory);

        $products = $this->getDependencyContainer()
            ->createProductTable($locale, $idCategory);

        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategories->render(),
            'productsTable' => $products->render(),
        ]);
    }

    /**
     * @param $existingCategoryNode
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $locale
     */
    protected function createOrUpdateCategoryNode($existingCategoryNode, NodeTransfer $categoryNodeTransfer, LocaleTransfer $locale)
    {
        /**
         * @var SpyCategoryNode $existingCategoryNode
         */
        if ($existingCategoryNode) {
            $categoryNodeTransfer->setIdCategoryNode($existingCategoryNode->getIdCategoryNode());
            $this->getDependencyContainer()
                ->createCategoryFacade()
                ->moveCategoryNode($categoryNodeTransfer, $locale);
        } else {
            $newData = $categoryNodeTransfer->toArray();
            unset($newData['id_category_node']);
            $categoryNodeTransfer = (new NodeTransfer())->fromArray($newData, true);
            $this->getDependencyContainer()
                ->createCategoryFacade()
                ->createCategoryNode($categoryNodeTransfer, $locale);
        }
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param LocaleTransfer $locale
     * @param array $parentIdList
     * @param array $addProductsMappingCollection
     * @param array $removeProductMappingCollection
     */
    protected function updateCategoryParents(
        CategoryTransfer $categoryTransfer, 
        LocaleTransfer $locale, 
        array $parentIdList, 
        array $addProductsMappingCollection, 
        array $removeProductMappingCollection
    )
    {
        $existingParents = $this->getDependencyContainer()
            ->createCategoryFacade()
            ->getNodesByIdCategory($categoryTransfer->getIdCategory());

        //remove deselected parents
        foreach ($existingParents as $parent) {
            if (!array_key_exists($parent->getFkParentCategoryNode(), $parentIdList)) {
                $this->getDependencyContainer()
                    ->createCategoryFacade()
                    ->deleteNode($parent->getIdCategoryNode(), $locale);
            }
        }

        if (!empty($removeProductMappingCollection)) {
            //de assign products
            $this->getDependencyContainer()
                ->createProductCategoryFacade()
                ->removeProductCategoryMappings($categoryTransfer->getIdCategory(), $removeProductMappingCollection);
        }

        if (!empty($addProductsMappingCollection)) {
            //assign products
            $this->getDependencyContainer()
                ->createProductCategoryFacade()
                ->createProductCategoryMappings($categoryTransfer->getIdCategory(), $addProductsMappingCollection);
        }
    }
}
