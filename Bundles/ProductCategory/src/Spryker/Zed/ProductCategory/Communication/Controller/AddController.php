<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\ProductCategory\ProductCategoryConstants;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method ProductCategoryFacade getFacade()
 * @method ProductCategoryCommunicationFactory getFactory()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idParentNode = $request->get(ProductCategoryConstants::PARAM_ID_PARENT_NODE);

        $form = $this->getFactory()
            ->createCategoryFormAdd($idParentNode);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $localeTransfer = $this->getFactory()
                ->getCurrentLocale();

            $categoryTransfer = $this->createCategoryTransferFromData($form->getData());
            $categoryNodeTransfer = $this->createCategoryNodeTransferFromData($form->getData());

            $idCategory = $this->getFacade()
                ->addCategory($categoryTransfer, $categoryNodeTransfer, $localeTransfer);

            $this->addSuccessMessage('The category was added successfully.');

            return $this->redirectResponse('/product-category/edit?id-category=' . $idCategory);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'showProducts' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productCategoryTableAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConstants::PARAM_ID_CATEGORY);
        $locale = $this->getFactory()
            ->getCurrentLocale();

        $productCategoryTable = $this->getFactory()
            ->createProductCategoryTable($locale, $idCategory);

        return $this->jsonResponse(
            $productCategoryTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productTableAction(Request $request)
    {
        $idCategory = $request->get(ProductCategoryConstants::PARAM_ID_CATEGORY);
        $locale = $this->getFactory()
            ->getCurrentLocale();

        $productTable = $this->getFactory()
            ->createProductTable($locale, $idCategory);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategoryTransferFromData(array $data)
    {
        return (new CategoryTransfer())
            ->fromArray($data, true);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function createCategoryNodeTransferFromData(array $data)
    {
        return (new NodeTransfer())
            ->fromArray($data, true);
    }

}
