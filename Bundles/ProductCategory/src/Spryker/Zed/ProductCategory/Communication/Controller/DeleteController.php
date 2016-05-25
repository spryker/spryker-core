<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Spryker\Shared\ProductCategory\ProductCategoryConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacade getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer getQueryContainer()
 */
class DeleteController extends EditController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(ProductCategoryConstants::PARAM_ID_CATEGORY));

        if (!$this->categoryExists($idCategory)) {
            $this->addErrorMessage(sprintf('The category with id "%s" does not exist.', $idCategory));

            return new RedirectResponse('/category');
        }

        $dataProvider = $this->getFactory()->createCategoryFormDeleteDataProvider();

        $form = $this
            ->getFactory()
            ->createCategoryFormDelete(
                $dataProvider->getData($idCategory),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $localeTransfer = $this->getFactory()->getCurrentLocale();

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
        $categoryCount = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->count();

        if ($categoryCount === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idCategory
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    protected function getViewData($idCategory, FormInterface $form)
    {
        $locale = $this->getFactory()
            ->getCurrentLocale();

        $categoryEntity = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne();

        $productCategoryTable = $this->getFactory()
            ->createProductCategoryTable($locale, $idCategory);

        $productTable = $this->getFactory()
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
