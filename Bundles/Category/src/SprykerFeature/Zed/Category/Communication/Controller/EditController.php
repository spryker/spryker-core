<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryQueryContainer getQueryContainer()
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
        $idCategory = $request->get(CategoryConfig::PARAM_ID_CATEGORY);

        $categoryExists = $this->getQueryContainer()->queryCategoryById($idCategory)->count() > 0;
        
        if (!$categoryExists) {
            $this->addErrorMessage(sprintf('The category you are trying to edit %s does not exist.', $idCategory));
            return new RedirectResponse('/category');
        }

        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        /**
         * @var Form $form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryFormEdit($idCategory)
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $categoryTransfer = (new CategoryTransfer())->fromArray($form->getData(), true);
            $this->getFacade()->updateCategory($categoryTransfer, $locale);

            $data = $form->getData();
            $parentIdList = $form->getData()['fk_parent_category_node'];
            
            foreach ($parentIdList as $parentNodeId) {
                $data['fk_parent_category_node'] = $parentNodeId;
                $data['fk_category'] = $categoryTransfer->getIdCategory();
                
                $categoryNodeTransfer = (new NodeTransfer())->fromArray($data, true);
                $existingCategoryNode = $this->getFacade()->getNodeByIdCategoryAndParentNode($categoryTransfer->getIdCategory(), $parentNodeId);
                
                if ($existingCategoryNode) {
                    $categoryNodeTransfer->setIdCategoryNode($existingCategoryNode->getIdCategoryNode());
                    $this->getFacade()->moveCategoryNode($categoryNodeTransfer, $locale);
                }
                else {
                    $new_data = $data;
                    unset($new_data['id_category_node']);
                    $categoryNodeTransfer = (new NodeTransfer())->fromArray($new_data, true);
                    $this->getFacade()->createCategoryNode($categoryNodeTransfer, $locale);
                }
            }
            
            $existingParents = $this->getFacade()->getNodesByIdCategory($categoryTransfer->getIdCategory());
            $parentIdList = array_flip($parentIdList);
            
            //remove deselected parents
            foreach ($existingParents as $parent) {
                if (!array_key_exists($parent->getFkParentCategoryNode(), $parentIdList)) {
                    $this->getFacade()->deleteNode($parent->getIdCategoryNode(), $locale);
                }
            }

            $this->addSuccessMessage('The category was saved successfully.');
            
            return $this->redirectResponse('/category');
        }

        $productCategories = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory)
        ;

        $products = $this->getDependencyContainer()
            ->createProductTable($locale, $idCategory)
        ;
        
        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategories->render(),
            'productsTable' => $products->render(),
        ]);
    }

}
