<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ModelSspAssetAttachmentTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetCreateForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
    protected const MESSAGE_SSP_MODEL_CREATE_ERROR = 'Something went wrong, please try again.';

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

        if ($request->get(SspAssetCreateForm::FORM_NAME)) {
            $formOptions = $this->getFactory()->createSspAssetFormDataProvider()->expandOptionsWithSubmittedData(
                $formOptions,
                $request->get(SspAssetCreateForm::FORM_NAME),
            );
        }

        $form = $this->getFactory()->createSspAssetCreateForm(null, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleFormSubmission($form);
        }

        return [
            'sspAssetForm' => $form->createView(),
        ];
    }

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

        $sspAssetCollectionResponseTransfer = $this->getFacade()->createSspAssetCollection($sspAssetCollectionRequestTransfer);
        $this->addSuccessMessage(static::MESSAGE_SSP_ASSET_CREATE_SUCCESS);

        $createSspModel = $sspAssetForm->get(SspAssetCreateForm::FIELD_CREATE_MODEL)->getData();

        if (!$createSspModel) {
            return $this->redirectResponse(sprintf(static::ROUTE_SSP_ASSET_DETAIL, $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current()->getIdSspAsset()));
        }

        $this->createSspModelForAsset($sspAssetTransfer);
        $this->addSuccessMessage(static::MESSAGE_SSP_ASSET_CREATE_SUCCESS);

        return $this->redirectResponse(sprintf(static::ROUTE_SSP_ASSET_DETAIL, $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current()->getIdSspAsset()));
    }

    protected function createSspModelForAsset(SspAssetTransfer $sspAssetTransfer): void
    {
        $sspModelCollectionResponseTransfer = $this->getFacade()->createSspModelCollection(
            (new SspModelCollectionRequestTransfer())
                ->addSspModel((new SspModelTransfer())->setName($sspAssetTransfer->getName())),
        );

        if ($sspModelCollectionResponseTransfer->getSspModels()->count() > 0) {
            $createdSspModel = $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current();

            $this->getFacade()->updateSspModelCollection(
                (new SspModelCollectionRequestTransfer())
                    ->addSspAssetToBeAttached(
                        (new ModelSspAssetAttachmentTransfer())
                            ->setSspModel($createdSspModel)
                            ->setSspAsset($sspAssetTransfer),
                    ),
            );
        }
    }
}
