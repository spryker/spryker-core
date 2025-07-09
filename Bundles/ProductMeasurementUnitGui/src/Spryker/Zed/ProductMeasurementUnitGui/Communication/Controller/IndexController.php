<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitConditionsTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitGui\Communication\ProductMeasurementUnitGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_MEASUREMENT_UNIT_CREATED = 'Measurement unit created successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_MEASUREMENT_UNIT_UPDATED = 'Measurement unit updated successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_MEASUREMENT_UNIT_DELETED = 'Measurement unit [%s] deleted.';

    /**
     * @var string
     */
    protected const MESSAGE_MEASUREMENT_UNIT_NOT_FOUND = 'Measurement unit [%s] not found.';

    /**
     * @var string
     */
    protected const MESSAGE_UNEXPECTED = 'Unexpected error';

    /**
     * @var string
     */
    protected const MESSAGE_MISSING_CODE = 'Missing code parameter';

    /**
     * @var string
     */
    protected const MESSAGE_PLACEHOLDER = '%s';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_CODE = 'code';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_CODE = 'code';

    /**
     * @uses \Spryker\Zed\ProductMeasurementUnitGui\Communication\Controller\IndexController::indexAction()
     *
     * @var string
     */
    protected const PAGE_LIST = '/product-measurement-unit-gui';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $productMeasurementUnitTable = $this->getFactory()->createProductMeasurementUnitTable();

        return $this->viewResponse(['productMeasurementUnitTable' => $productMeasurementUnitTable->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $productMeasurementUnitTable = $this->getFactory()->createProductMeasurementUnitTable();

        return $this->jsonResponse($productMeasurementUnitTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function createAction(Request $request): Response|array
    {
        $form = $this->getFactory()->createProductMeasurementUnitForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->handleCreateForm($form);

            if ($response instanceof RedirectResponse) {
                return $response;
            }
        }

        return $this->viewResponse(['form' => $form->createView()]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function handleCreateForm(FormInterface $form): RedirectResponse|null
    {
        $productMeasurementUnitCollectionRequestTransfer = (new ProductMeasurementUnitCollectionRequestTransfer())
            ->addProductMeasurementUnit($form->getData());

        $productMeasurementUnitCollectionResponseTransfer = $this->getFactory()
            ->getProductMeasurementUnitFacade()
            ->createProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        if ($this->isResponseSuccessful($productMeasurementUnitCollectionResponseTransfer)) {
            $this->addSuccessMessage(static::MESSAGE_MEASUREMENT_UNIT_CREATED);

            return $this->redirectResponse(static::PAGE_LIST);
        }

        $this->addErrorMessages($productMeasurementUnitCollectionResponseTransfer->getErrors());

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function editAction(Request $request): Response|array
    {
        $code = (string)$request->query->get(static::REQUEST_PARAM_CODE);

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitConditionsTransfer())
            ->addCode($code);
        $productMeasurementUnitCriteriaTransfer = (new ProductMeasurementUnitCriteriaTransfer())
            ->setProductMeasurementUnitConditions($productMeasurementUnitConditionsTransfer);

        $productMeasurementUnitCollectionResponseTransfer = $this->getFactory()
            ->getProductMeasurementUnitFacade()
            ->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer);

        $productMeasurementUnitTransfer = $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits()[0] ?? null;
        if (!$productMeasurementUnitTransfer) {
            $this->addErrorMessage(static::MESSAGE_MEASUREMENT_UNIT_NOT_FOUND, [static::MESSAGE_PLACEHOLDER => $code]);

            return $this->redirectResponse(static::PAGE_LIST);
        }

        $form = $this->getFactory()->createProductMeasurementUnitForm($productMeasurementUnitTransfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->handleEditForm($form, $code);

            if ($response instanceof RedirectResponse) {
                return $response;
            }
        }

        return $this->viewResponse(['form' => $form->createView()]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $code
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function handleEditForm(FormInterface $form, string $code): RedirectResponse|null
    {
        $productMeasurementUnitTransfer = $form->getData();
        $productMeasurementUnitTransfer->setCode($code); // For integrity reasons, form data is ignored.

        $productMeasurementUnitCollectionRequestTransfer = (new ProductMeasurementUnitCollectionRequestTransfer())
            ->addProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementUnitCollectionResponseTransfer = $this->getFactory()
            ->getProductMeasurementUnitFacade()
            ->updateProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        if ($this->isResponseSuccessful($productMeasurementUnitCollectionResponseTransfer)) {
            $this->addSuccessMessage(static::MESSAGE_MEASUREMENT_UNIT_UPDATED);

            return $this->redirectResponse(static::PAGE_LIST);
        }

        $this->addErrorMessages($productMeasurementUnitCollectionResponseTransfer->getErrors());

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request): Response
    {
        $code = (string)$request->query->get(static::REQUEST_PARAM_CODE);

        $productMeasurementUnitCollectionDeleteCriteriaTransfer = (new ProductMeasurementUnitCollectionDeleteCriteriaTransfer())
            ->addCode($code);

        $productMeasurementUnitCollectionResponseTransfer = $this->getFactory()
            ->getProductMeasurementUnitFacade()
            ->deleteProductMeasurementUnitCollection($productMeasurementUnitCollectionDeleteCriteriaTransfer);

        if ($this->isResponseSuccessful($productMeasurementUnitCollectionResponseTransfer)) {
            $this->addSuccessMessage(static::MESSAGE_MEASUREMENT_UNIT_DELETED, [static::MESSAGE_PLACEHOLDER => $code]);
        } else {
            $this->addErrorMessages($productMeasurementUnitCollectionResponseTransfer->getErrors());
        }

        return $this->redirectResponse(static::PAGE_LIST);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return void
     */
    protected function addErrorMessages(ArrayObject $errorTransfers): void
    {
        foreach ($errorTransfers as $errorTransfer) {
            $this->addErrorMessage(
                $errorTransfer->getMessage() ?? static::MESSAGE_UNEXPECTED,
                [static::MESSAGE_PLACEHOLDER => $errorTransfer->getParameters()[static::MESSAGE_PARAM_CODE] ?? static::MESSAGE_MISSING_CODE],
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $response
     *
     * @return bool
     */
    protected function isResponseSuccessful(ProductMeasurementUnitCollectionResponseTransfer $response): bool
    {
        return count($response->getErrors()) === 0 && count($response->getProductMeasurementUnits()) === 1;
    }
}
