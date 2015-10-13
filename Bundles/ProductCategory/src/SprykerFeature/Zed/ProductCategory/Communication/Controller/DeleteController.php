<?php

namespace SprykerFeature\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Propel;
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
class DeleteController extends EditController
{
    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConfig::PARAM_ID_CATEGORY);

        $currentCategory = $this->getDependencyContainer()
                ->createCategoryQueryContainer()
                ->queryCategoryById($idCategory)
                ->findOne();

        if (!$currentCategory) {
            $this->addErrorMessage(sprintf('The category you are trying to delete %s does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $locale = $this->getDependencyContainer()
            ->createCurrentLocale();

        /**
         * @var Form
         */
        $form = $this->getDependencyContainer()
            ->createCategoryFormDelete($idCategory);

        $form->handleRequest();

        if ($form->isValid()) {
            $connection = Propel::getConnection();
            $connection->beginTransaction();

            $data = $form->getData();

            $currentCategoryTransfer = (new CategoryTransfer())
                ->fromArray($data, true);

            if ($data['delete_children']) {
                $this->getDependencyContainer()
                    ->createProductCategoryFacade()
                    ->deleteCategoryFull($currentCategoryTransfer, $locale)
                ;
            } else {

                die('move');
                $currentCategoryNodeTransfer = $this->updateCategoryNode($currentCategoryTransfer, $locale, $data);

                $parentIdList[] = $currentCategoryNodeTransfer->getFkParentCategoryNode();
                $parentIdList = array_flip($parentIdList);
                $this->removeDeselectedCategoryAdditionalParents(
                    $currentCategoryTransfer,
                    $locale,
                    $parentIdList
                );
            }


            $this->addSuccessMessage('The category was deleted successfully.');

            $connection->commit();

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
            'showProducts' => false,
            'currentCategory' => $currentCategory->toArray()
        ]);
    }

}
