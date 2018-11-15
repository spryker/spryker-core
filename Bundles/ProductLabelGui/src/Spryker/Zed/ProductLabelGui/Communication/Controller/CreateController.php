<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiFacadeInterface getFacade()
 */
class CreateController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $productLabelAggregateForm = $this->createProductLabelAggregateForm();
        $isFormSuccessfullyHandled = $this->handleProductLabelAggregateForm(
            $request,
            $productLabelAggregateForm
        );

        if ($isFormSuccessfullyHandled) {
            return $this->redirectResponse('/product-label-gui');
        }

        return $this->viewResponse([
            'productLabelFormTabs' => $this->getFactory()->createProductLabelFormTabs()->createView(),
            'aggregateForm' => $productLabelAggregateForm->createView(),
            'availableProductTable' => $this->getFactory()->createAvailableProductTable()->render(),
            'assignedProductTable' => $this->getFactory()->createAssignedProductTable()->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createProductLabelAggregateForm()
    {
        $aggregateFormDataProvider = $this
            ->getFactory()
            ->createProductLabelAggregateFormDataProvider();

        $aggregateForm = $this
            ->getFactory()
            ->getProductLabelAggregateForm(
                $aggregateFormDataProvider->getData(),
                $aggregateFormDataProvider->getOptions()
            );

        return $aggregateForm;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $aggregateForm
     *
     * @return bool
     */
    protected function handleProductLabelAggregateForm(Request $request, FormInterface $aggregateForm)
    {
        $aggregateForm->handleRequest($request);

        if (!$aggregateForm->isValid()) {
            return false;
        }

        /** @var \Generated\Shared\Transfer\ProductLabelAggregateFormTransfer $aggregateFormTransfer */
        $aggregateFormTransfer = $aggregateForm->getData();

        $productLabelTransfer = $this->storeProductLabel($aggregateFormTransfer->getProductLabel());
        if (!$productLabelTransfer->getIsDynamic()) {
            $this->storeRelatedProduct(
                $aggregateFormTransfer->getProductAbstractRelations(),
                $productLabelTransfer->getIdProductLabel()
            );
        }

        $this->addSuccessMessage(sprintf(
            'Product label #%d successfully created.',
            $productLabelTransfer->getIdProductLabel()
        ));

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function storeProductLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this
            ->getFactory()
            ->getProductLabelFacade()
            ->createLabel($productLabelTransfer);

        return $productLabelTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer $relationsTransfer
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function storeRelatedProduct(
        ProductLabelProductAbstractRelationsTransfer $relationsTransfer,
        $idProductLabel
    ) {
        if (!count($relationsTransfer->getIdsProductAbstractToAssign())) {
            return;
        }

        $this
            ->getFactory()
            ->getProductLabelFacade()
            ->addAbstractProductRelationsForLabel(
                $idProductLabel,
                $relationsTransfer->getIdsProductAbstractToAssign()
            );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductTableAction()
    {
        $availableProductTable = $this->getFactory()->createAvailableProductTable();

        return $this->jsonResponse($availableProductTable->fetchData());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductTableAction()
    {
        $assignedProductTable = $this->getFactory()->createAssignedProductTable();

        return $this->jsonResponse($assignedProductTable->fetchData());
    }
}
