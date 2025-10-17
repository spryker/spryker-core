<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class UpdateModelController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_UPDATE_SUCCESS = 'Model has been successfully updated.';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_UPDATE_ERROR = 'Something went wrong, please try again.';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_NOT_FOUND = 'Model not found.';

    /**
     * @var string
     */
    protected const ROUTE_SSP_MODEL_LIST = '/self-service-portal/list-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewModelController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_MODEL_VIEW = '/self-service-portal/view-model?id-ssp-model=%s';

    /**
     * @var string
     */
    protected const PARAM_ID_SSP_MODEL = 'id-ssp-model';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idSspModel = $this->castId($request->query->get(static::PARAM_ID_SSP_MODEL));

        if (!$idSspModel) {
            $this->addErrorMessage(static::MESSAGE_SSP_MODEL_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_SSP_MODEL_LIST);
        }

        $sspModelTransfer = $this->getSspModel($idSspModel);

        if (!$sspModelTransfer) {
            $this->addErrorMessage(static::MESSAGE_SSP_MODEL_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_SSP_MODEL_LIST);
        }

        $form = $this->getFactory()->createSspModelForm(
            $sspModelTransfer,
            $this->getFactory()->createSspModelFormDataProvider()->getOptions($sspModelTransfer),
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleFormSubmission($form, $sspModelTransfer);
        }

        return [
            'sspModelForm' => $form->createView(),
            'sspModel' => $sspModelTransfer,
            'deleteForm' => $this->getFactory()->createDeleteSspModelForm()->createView(),
            'sspModelTabs' => $this->getFactory()->createSspModelTabs()->createView(),
            'attachedAssetsTable' => $this->getFactory()->createAttachedAssetsTable($sspModelTransfer)->render(),
            'attachedProductListsTable' => $this->getFactory()->createAttachedProductListsTable($idSspModel)->render(),
        ];
    }

    public function attachedSspAssetTableAction(Request $request): JsonResponse
    {
        $idSspModel = $request->query->getInt(static::PARAM_ID_SSP_MODEL);

        $attachedSspAssetTableDataProvider = $this->getFactory()->createAttachedSspAssetTableDataProvider();

        return $this->jsonResponse(
            $attachedSspAssetTableDataProvider->getAttachedSspAssetTableData($idSspModel),
        );
    }

    public function attachedProductListsTableAction(Request $request): JsonResponse
    {
        $idSspModel = $request->query->getInt(static::PARAM_ID_SSP_MODEL);

        $attachedProductListsTable = $this->getFactory()->createAttachedProductListsTable($idSspModel);

        return $this->jsonResponse(
            $attachedProductListsTable->fetchData(),
        );
    }

    protected function getSspModel(int $idSspModel): ?SspModelTransfer
    {
        $sspModelCollectionTransfer = $this->getFacade()->getSspModelCollection(
            (new SspModelCriteriaTransfer())
                ->setSspModelConditions(
                    (new SspModelConditionsTransfer())
                        ->setSspModelIds([$idSspModel]),
                )->setWithImageFile(true),
        );

        if ($sspModelCollectionTransfer->getSspModels()->count() === 0) {
            return null;
        }

        return $sspModelCollectionTransfer->getSspModels()->getIterator()->current();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sspModelForm
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function handleFormSubmission(FormInterface $sspModelForm, SspModelTransfer $sspModelTransfer): RedirectResponse|array
    {
        $updatedSspModelTransfer = $this->getFactory()->createSspModelFormDataToTransferMapper()->mapFormDataToSspModelTransfer(
            $sspModelForm,
            $sspModelTransfer,
        );

        $sspModelCollectionRequestTransfer = new SspModelCollectionRequestTransfer();
        $sspModelCollectionRequestTransfer->addSspModel($updatedSspModelTransfer);

        $sspModelCollectionResponseTransfer = $this->getFacade()->updateSspModelCollection($sspModelCollectionRequestTransfer);

        if ($sspModelCollectionResponseTransfer->getErrors()->count() > 0) {
            foreach ($sspModelCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return [
                'deleteForm' => $this->getFactory()->createDeleteSspModelForm()->createView(),
                'sspModelForm' => $sspModelForm->createView(),
                'sspModel' => $sspModelTransfer,
                'sspModelTabs' => $this->getFactory()->createSspModelTabs()->createView(),
                'attachedAssetsTable' => $this->getFactory()->createAttachedAssetsTable($sspModelTransfer)->render(),
                'attachedProductListsTable' => $this->getFactory()->createAttachedProductListsTable($sspModelTransfer->getIdSspModelOrFail())->render(),
            ];
        }

        $this->addSuccessMessage(static::MESSAGE_SSP_MODEL_UPDATE_SUCCESS);

        return $this->redirectResponse(sprintf(static::ROUTE_SSP_MODEL_VIEW, $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current()->getIdSspModel()));
    }
}
