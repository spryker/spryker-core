<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class DeleteModelController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_SSP_MODEL_DELETE = 'Model successfully deleted';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_SSP_MODEL_DELETE = 'Model cannot be deleted';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListModelController::indexAction()
     *
     * @var string
     */
    protected const URL_REDIRECT_SSP_MODEL_PAGE = '/self-service-portal/list-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\DeleteModelController::deleteAction()
     *
     * @var string
     */
    protected const ROUTE_DELETE_SSP_MODEL = '/self-service-portal/delete-model/delete';

    /**
     * @var string
     */
    protected const PARAM_ID_SSP_MODEL = 'id-ssp-model';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function confirmDeleteAction(Request $request): array|RedirectResponse
    {
        $idSspModel = $request->query->getInt(static::PARAM_ID_SSP_MODEL);

        $sspModelCriteriaTransfer = (new SspModelCriteriaTransfer())
            ->setSspModelConditions(
                (new SspModelConditionsTransfer())->setSspModelIds([$idSspModel]),
            );

        $sspModelCollectionTransfer = $this->getFacade()->getSspModelCollection($sspModelCriteriaTransfer);

        if ($sspModelCollectionTransfer->getSspModels()->count() === 0) {
            $this->addErrorMessage(static::MESSAGE_ERROR_SSP_MODEL_DELETE);

            return $this->redirectResponse(static::URL_REDIRECT_SSP_MODEL_PAGE);
        }

        /** @var \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer */
        $sspModelTransfer = $sspModelCollectionTransfer->getSspModels()->getIterator()->current();

        $deleteForm = $this->getFactory()->createDeleteSspModelForm()->createView();

        return $this->viewResponse([
            'sspModel' => $sspModelTransfer,
            'deleteForm' => $deleteForm,
            'backUrl' => static::URL_REDIRECT_SSP_MODEL_PAGE,
            'deleteModelRoute' => static::ROUTE_DELETE_SSP_MODEL,
        ]);
    }

    public function deleteAction(Request $request): RedirectResponse
    {
        $deleteForm = $this->getFactory()->createDeleteSspModelForm()->handleRequest($request);

        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse(static::URL_REDIRECT_SSP_MODEL_PAGE);
        }

        $idSspModel = $request->query->getInt(static::PARAM_ID_SSP_MODEL);
        if (!$idSspModel) {
            $this->addErrorMessage(static::MESSAGE_ERROR_SSP_MODEL_DELETE);

            return $this->redirectResponse(static::URL_REDIRECT_SSP_MODEL_PAGE);
        }

        $sspModelCollectionDeleteCriteriaTransfer = (new SspModelCollectionDeleteCriteriaTransfer())
            ->setSspModelIds([$idSspModel])
            ->setIsTransactional(true);

        $sspModelCollectionResponseTransfer = $this->getFacade()
            ->deleteSspModelCollection($sspModelCollectionDeleteCriteriaTransfer);

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_SSP_MODEL_DELETE);

        return $this->redirectResponse(static::URL_REDIRECT_SSP_MODEL_PAGE);
    }
}
