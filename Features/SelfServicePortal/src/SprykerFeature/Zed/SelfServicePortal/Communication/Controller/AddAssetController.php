<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AddAssetController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_SSP_ASSET_CREATE_SUCCESS = 'Asset has been successfully created.';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_ASSET_CREATE_ERROR = 'Something went wrong, please try again.';

    /**
     * @var string
     */
    protected const ROUTE_SSP_ASSET_LIST = '/self-service-portal/list-asset';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewAssetController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_ASSET_DETAIL = '/self-service-portal/view-asset?id-ssp-asset=%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $formOptions = [
            SspAssetForm::OPTION_STATUS_OPTIONS => array_flip($this->getFactory()->getConfig()->getAssetStatuses()),
        ];

        if ($request->get(SspAssetForm::FORM_NAME)) {
            $formOptions = $this->getFactory()->createSspAssetFormDataProvider()->expandOptionsWithSubmittedData(
                $formOptions,
                $request->get(SspAssetForm::FORM_NAME),
            );
        }

        $form = $this->getFactory()->createSspAssetForm(null, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleFormSubmission($form);
        }

        return [
            'sspAssetForm' => $form->createView(),
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

        foreach ($sspAssetForm->get(SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS)->getData() as $businessUnitIdToAssign) {
            $sspAssetTransfer->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitIdToAssign)),
            );
        }

        try {
            $sspAssetCollectionResponseTransfer = $this->getFacade()->createSspAssetCollection($sspAssetCollectionRequestTransfer);
            $this->addSuccessMessage(static::MESSAGE_SSP_ASSET_CREATE_SUCCESS);
        } catch (Throwable $e) {
            $this->addErrorMessage(static::MESSAGE_SSP_ASSET_CREATE_ERROR);

            return $this->redirectResponse(static::ROUTE_SSP_ASSET_LIST);
        }

        return $this->redirectResponse(sprintf(static::ROUTE_SSP_ASSET_DETAIL, $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current()->getIdSspAsset()));
    }
}
