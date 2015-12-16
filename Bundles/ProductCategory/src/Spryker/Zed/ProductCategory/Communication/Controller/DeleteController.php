<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;
use Spryker\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryDependencyContainer getCommunicationFactory()
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

        if (!$this->categoryExists($idCategory)) {
            $this->addErrorMessage(sprintf('The category with id "%s" does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $form = $this->getCommunicationFactory()
            ->createCategoryFormDelete($idCategory)
            ->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
            $localeTransfer = $this->getCommunicationFactory()
                ->createCurrentLocale();
            $this->getFacade()->deleteCategory(
                $data['id_category_node'],
                $data['fk_parent_category_node'],
                $data['delete_children'],
                $localeTransfer
            );

            return $this->redirectResponse('/category');
        }

        return $this->viewResponse($this->getViewData($idCategory, $form));
    }

    /**
     * @param int $idCategory
     *
     * @return bool
     */
    protected function categoryExists($idCategory)
    {
        $categoryCount = $this->getCommunicationFactory()
            ->createCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->count();

        if ($categoryCount === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idCategory
     * @param Form $form
     *
     * @return array
     */
    protected function getViewData($idCategory, Form $form)
    {
        $locale = $this->getCommunicationFactory()
            ->createCurrentLocale();

        $categoryEntity = $this->getCommunicationFactory()
            ->createCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne();

        $productCategoryTable = $this->getCommunicationFactory()
            ->createProductCategoryTable($locale, $idCategory);

        $productTable = $this->getCommunicationFactory()
            ->createProductTable($locale, $idCategory);

        return [
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $productCategoryTable->render(),
            'productsTable' => $productTable->render(),
            'showProducts' => false,
            'currentCategory' => $categoryEntity->toArray(),
            'paths' => $this->getPaths($categoryEntity, $locale),
            'products' => $this->getProducts($categoryEntity, $locale),
            'blocks' => $this->getBlocks($categoryEntity, $locale),
        ];
    }

}
