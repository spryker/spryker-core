<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AddModelController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_CREATE_SUCCESS = 'Model has been successfully created.';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_MODEL_CREATE_ERROR = 'Something went wrong, please try again.';

    /**
     * @var string
     */
    protected const ROUTE_SSP_MODEL_LIST = '/self-service-portal/list-model';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewModelController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_MODEL_DETAIL = '/self-service-portal/view-model?id-ssp-model=%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $form = $this->getFactory()->createSspModelForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleFormSubmission($form);
        }

        return [
            'sspModelForm' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sspModelForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function handleFormSubmission(FormInterface $sspModelForm): RedirectResponse|array
    {
        $sspModelTransfer = $this->getFactory()->createSspModelFormDataToTransferMapper()->mapFormDataToSspModelTransfer(
            $sspModelForm,
            $sspModelForm->getData(),
        );

        $sspModelCollectionRequestTransfer = new SspModelCollectionRequestTransfer();
        $sspModelCollectionRequestTransfer->addSspModel($sspModelTransfer);

        $sspModelCollectionResponseTransfer = $this->getFacade()->createSspModelCollection($sspModelCollectionRequestTransfer);

        if ($sspModelCollectionResponseTransfer->getErrors()->count() > 0) {
            foreach ($sspModelCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return [
                'sspModelForm' => $sspModelForm->createView(),
            ];
        }

        $this->addSuccessMessage(static::MESSAGE_SSP_MODEL_CREATE_SUCCESS);

        return $this->redirectResponse(sprintf(static::ROUTE_SSP_MODEL_DETAIL, $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current()->getIdSspModel()));
    }
}
