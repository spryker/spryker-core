<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\ProductCategory\Communication\ProductCategoryDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategory\ProductCategoryConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idParentNode = $request->get(ProductCategoryConfig::PARAM_ID_PARENT_NODE);

        $form = $this->getDependencyContainer()
            ->createCategoryFormAdd($idParentNode)
        ;
        $form->handleRequest();

        if ($form->isValid()) {
            $localeTransfer = $this->getDependencyContainer()
                ->createCurrentLocale()
            ;

            $categoryTransfer = $this->createCategoryTransferFromData($form->getData());
            $categoryNodeTransfer = $this->createCategoryNodeTransferFromData($form->getData());

            $idCategory = $this->getFacade()
                ->addCategory($categoryTransfer, $categoryNodeTransfer, $localeTransfer)
            ;

            $this->addSuccessMessage('The category was added successfully.');

            return $this->redirectResponse('/product-category/edit?id-category=' . $idCategory);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'showProducts' => false,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function productCategoryTableAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConfig::PARAM_ID_CATEGORY);
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $productCategoryTable = $this->getDependencyContainer()
            ->createProductCategoryTable($locale, $idCategory)
        ;

        return $this->jsonResponse(
            $productCategoryTable->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConfig::PARAM_ID_CATEGORY);
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale()
        ;

        $productTable = $this->getDependencyContainer()
            ->createProductTable($locale, $idCategory)
        ;

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @param array $data
     *
     * @return CategoryTransfer
     */
    protected function createCategoryTransferFromData(array $data)
    {
        return (new CategoryTransfer())
            ->fromArray($data, true);
    }

    /**
     * @param array $data
     *
     * @return NodeTransfer
     */
    protected function createCategoryNodeTransferFromData(array $data)
    {
        return (new NodeTransfer())
            ->fromArray($data, true);
    }

}
