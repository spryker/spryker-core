<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductCategory\Communication\Table\ProductCategoryTable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface getRepository()
 */
class AssignController extends AbstractController
{
    public const PARAM_ID_CATEGORY = 'id-category';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(ProductCategoryTable::PARAM_ID_CATEGORY));
        $categoryEntity = $this->getCategoryEntity($idCategory);

        if (!$categoryEntity) {
            return new RedirectResponse($this->getFactory()->getCategoryFacade()->getCategoryListUrl());
        }

        $form = $this->getForm($idCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->updateCategoryData($form->getData())) {
                $this->addSuccessMessage('The category was saved successfully.');

                return $this->redirectResponse('/product-category/assign?id-category=' . $idCategory);
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrorMessage('Please make sure required fields are properly filled in');
        }

        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryProductsTable = $this->getCategoryProductsTable($idCategory, $localeTransfer);
        $productsTable = $this->getProductsTable($idCategory, $localeTransfer);

        $categoryFacade = $this->getFactory()->getCategoryFacade();
        $categoryPath = $categoryFacade->getNodePath($idCategory, $localeTransfer);

        return $this->viewResponse([
            'idCategory' => $idCategory,
            'form' => $form->createView(),
            'productCategoriesTable' => $categoryProductsTable->render(),
            'productsTable' => $productsTable->render(),
            'currentCategory' => $categoryEntity->toArray(),
            'categoryPath' => $categoryPath,
            'currentLocale' => $localeTransfer->getLocaleName(),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory|null
     */
    protected function getCategoryEntity($idCategory)
    {
        $categoryEntity = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategoryById($idCategory)
            ->findOne();

        if (!$categoryEntity) {
            $this->addErrorMessage('The category with id "%s" does not exist.', ['%s' => $idCategory]);

            return null;
        }

        return $categoryEntity;
    }

    /**
     * @param int $idCategory
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm($idCategory)
    {
        return $this->getFactory()->createAssignForm([
            'id_category' => $idCategory,
        ]);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductCategory\Communication\Table\ProductCategoryTable
     */
    protected function getCategoryProductsTable($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createProductCategoryTable($localeTransfer, $idCategory);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductCategory\Communication\Table\ProductTable
     */
    protected function getProductsTable($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createProductTable($localeTransfer, $idCategory);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function updateCategoryData(array $data)
    {
        $idCategory = $this->castId($data['id_category']);

        $this->updateProductCategoryMappings($idCategory, $data);
        $this->updateProductOrder($idCategory, (array)json_decode($data['product_order'], true));

        return true;
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
        $this->getFacade()->updateProductMappingsOrder($idCategory, $productOrder);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productCategoryTableAction(Request $request)
    {
        $idCategory = $this->castId($request->get(ProductCategoryTable::PARAM_ID_CATEGORY));
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $productCategoryTable = $this->getCategoryProductsTable($idCategory, $localeTransfer);

        return $this->jsonResponse($productCategoryTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idCategory = $this->castId($request->get(ProductCategoryTable::PARAM_ID_CATEGORY));
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $productTable = $this->getProductsTable($idCategory, $localeTransfer);

        return $this->jsonResponse($productTable->fetchData());
    }
}
