<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class UpdateAssetController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SSP_ASSET = 'id-ssp-asset';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_ASSET_UPDATE_SUCCESS = 'Asset has been successfully updated.';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_ASSET_NOT_FOUND = 'Asset not found.';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewAssetController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_ASSET_DETAIL = '/self-service-portal/view-asset?id-ssp-asset=%s';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListAssetController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_ASSET_LIST = '/self-service-portal/list-asset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $idSspAsset = $this->castId($request->query->get(static::PARAM_ID_SSP_ASSET));

        $sspAssetTransfer = $this->getFactory()->createSspAssetFormDataProvider()->getData($idSspAsset);

        if ($sspAssetTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_SSP_ASSET_NOT_FOUND);

            return $this->redirectResponse(sprintf(static::ROUTE_SSP_ASSET_LIST));
        }

        $formOptions = $this->getFactory()->createSspAssetFormDataProvider()->getOptions($sspAssetTransfer);
        if ($request->get(SspAssetForm::FORM_NAME)) {
            $formOptions = $this->getFactory()->createSspAssetFormDataProvider()->expandOptionsWithSubmittedData(
                $formOptions,
                $request->get(SspAssetForm::FORM_NAME),
            );
        }

        $sspAssetForm = $this->getFactory()->createSspAssetForm($sspAssetTransfer, $formOptions);

        $sspAssetForm->handleRequest($request);

        if ($sspAssetForm->isSubmitted() && $sspAssetForm->isValid()) {
            return $this->handleFormSubmission($sspAssetForm);
        }

        return [
            'sspAssetForm' => $sspAssetForm->createView(),
            'sspAsset' => $sspAssetTransfer,
            'sspAssetTabs' => $this->getFactory()->createSspAssetTabs()->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sspAssetForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleFormSubmission(FormInterface $sspAssetForm): RedirectResponse
    {
        $sspAssetTransfer = $this->getFactory()->createSspAssetFormDataToTransferMapper()->mapFormDataToSspAssetTransfer(
            $sspAssetForm,
            $sspAssetForm->getData(),
        );

        $sspAssetCollectionRequestTransfer = new SspAssetCollectionRequestTransfer();
        $sspAssetCollectionRequestTransfer->addSspAsset($sspAssetTransfer);

        $this->getFactory()->createSspAssetFormDataToTransferMapper()->mapAssignmentsToSspAssetCollectionRequestTransfer(
            $sspAssetForm->get(SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS)->getData(),
            $sspAssetTransfer,
            $sspAssetCollectionRequestTransfer,
        );

        $sspAssetCollectionResponseTransfer = $this->getFacade()->updateSspAssetCollection($sspAssetCollectionRequestTransfer);

        if ($sspAssetCollectionResponseTransfer->getErrors()->count() > 0) {
            foreach ($sspAssetCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return $this->redirectResponse(
                sprintf(
                    '/ssp-asset-management/update?%s=%d',
                    static::PARAM_ID_SSP_ASSET,
                    $sspAssetTransfer->getIdSspAssetOrFail(),
                ),
            );
        }

        $this->addSuccessMessage(static::MESSAGE_SSP_ASSET_UPDATE_SUCCESS);

        return $this->redirectResponse(sprintf(static::ROUTE_SSP_ASSET_DETAIL, $sspAssetTransfer->getIdSspAssetOrFail()));
    }
}
