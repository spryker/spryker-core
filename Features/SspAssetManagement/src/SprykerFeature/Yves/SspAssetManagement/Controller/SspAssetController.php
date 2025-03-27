<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use InvalidArgumentException;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\CreateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\UnassignSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\UpdateSspAssetPermissionPlugin;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetBusinessUnitRelationsForm;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetSearchForm;
use SprykerFeature\Yves\SspAssetManagement\Plugin\Router\SspAssetRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface getClient()
 * @method \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementFactory getFactory()
 */
class SspAssetController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_CREATED = 'ssp_asset.success.created';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_UPDATED = 'ssp_asset.success.updated';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_CREATE_ERROR = 'ssp_asset.error.create';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_UPDATE_ERROR = 'ssp_asset.error.update';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_BUSINESS_UNIT_RELATION_UPDATED = 'ssp_asset.success.business_unit_relation_updated';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_CUSTOMER_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request): View|RedirectResponse
    {
        $sspAssetReference = (string)$request->query->get('reference');
        if (!$sspAssetReference) {
            throw new InvalidArgumentException('ssp_asset.error.reference_not_found');
        }

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('ssp_asset.error.company_user_not_found');
        }

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions(
                (new SspAssetConditionsTransfer())->addReference($sspAssetReference),
            )
            ->setInclude(
                (new SspAssetIncludeTransfer())
                    ->setWithCompanyBusinessUnit(true)
                    ->setWithAssignedBusinessUnits(true)
                    ->setWithSspInquiries(true),
            );

        $this->getFactory()->createSspAssetCustomerPermissionExpander()->expand(
            $sspAssetCriteriaTransfer,
            $companyUserTransfer,
        );

        $sspAssetCollectionTransfer = $this->getClient()->getSspAssetCollection(
            $sspAssetCriteriaTransfer,
        );

        /** @var \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer */
        $sspAssetTransfer = $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();

        if (!$sspAssetTransfer) {
            throw new NotFoundHttpException(sprintf(
                'Ssp Asset with provided Reference %s was not found.',
                $sspAssetReference,
            ));
        }

        if (!$this->getFactory()->createSspAssetCustomerPermissionChecker()->canViewAsset($sspAssetTransfer, $companyUserTransfer)) {
            throw new AccessDeniedHttpException('ssp_asset.access.denied');
        }

        $canBusinessUnitBeUnassigned = false;
        foreach ($sspAssetTransfer->getAssignments() as $sspAssetAssignmentTransfer) {
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit() === $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit()) {
                $canBusinessUnitBeUnassigned = true;
            }
        }

        return $this->view(
            [
                'sspAsset' => $sspAssetTransfer,
                'canBusinessUnitBeUnassigned' => $canBusinessUnitBeUnassigned,
                'unassignBusinessUnitForm' => $this->getFactory()->createSspAssetBusinessUnitRelationsForm([
                    SspAssetBusinessUnitRelationsForm::FIELD_BUSINESS_UNIT_ID => $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
                    SspAssetBusinessUnitRelationsForm::FIELD_ASSET_REFERENCE => $sspAssetReference,
                ])->createView(),
            ],
            [],
            '@SspAssetManagement/views/details/details.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): View|RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if (!$companyUserTransfer) {
            $this->addErrorMessage('company.error.company_user_not_found');

            return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_OVERVIEW);
        }

        if (!$this->can(CreateSspAssetPermissionPlugin::KEY)) {
            throw new AccessDeniedHttpException('ssp_asset.access.denied');
        }

        $sspAssetCreateForm = $this->getFactory()
            ->createAssetForm()
            ->handleRequest($request);

        if ($sspAssetCreateForm->isSubmitted() && $sspAssetCreateForm->isValid()) {
            $sspAssetTransfer = $this->getFactory()->createSspAssetFormDataToTransferMapper()->mapFormDataToSspAssetTransfer(
                $sspAssetCreateForm,
                $sspAssetCreateForm->getData(),
            );

            $sspAssetTransfer->addAssignment(
                (new SspAssetAssignmentTransfer())->setCompanyBusinessUnit(
                    $companyUserTransfer->getCompanyBusinessUnit(),
                ),
            );

            $sspAssetTransfer->setCompanyBusinessUnit($companyUserTransfer->getCompanyBusinessUnit());

            $sspAssetCollectionResponseTransfer = $this->getClient()->createSspAssetCollection(
                (new SspAssetCollectionRequestTransfer())->addSspAsset($sspAssetTransfer),
            );

            if (!$sspAssetCollectionResponseTransfer->getErrors()->count() && $sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_ASSET_CREATED);

                return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current()->getReference(),
                ]);
            }

            foreach ($sspAssetCollectionResponseTransfer->getErrors() as $error) {
                $this->addErrorMessage($error->getMessageOrFail());
            }

            if (!$sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addErrorMessage(static::GLOSSARY_KEY_ASSET_CREATE_ERROR);
            }
        }

        return $this->view(
            [
                'form' => $sspAssetCreateForm->createView(),
            ],
            [],
            '@SspAssetManagement/views/create/create.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request): View|RedirectResponse
    {
        $sspAssetReference = $request->get('reference');

        if (!$sspAssetReference) {
            throw new InvalidArgumentException('ssp_asset.error.reference_not_found');
        }

        $sspAssetFormDataProvider = $this->getFactory()->createSspAssetFormDataProvider();
        $sspAssetTransfer = $sspAssetFormDataProvider->getData($sspAssetReference);

        if (!$sspAssetTransfer) {
            throw new NotFoundHttpException('ssp_asset.error.not_found');
        }

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if (!$companyUserTransfer) {
            $this->addErrorMessage('company.error.company_user_not_found');

            return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_OVERVIEW);
        }

        if (!$this->can(UpdateSspAssetPermissionPlugin::KEY)) {
            throw new AccessDeniedHttpException('ssp_asset.access.denied');
        }

        $sspAssetUpdateForm = $this->getFactory()
            ->createAssetForm($sspAssetTransfer, $sspAssetFormDataProvider->getOptions($sspAssetTransfer))
            ->handleRequest($request);

        if ($sspAssetUpdateForm->isSubmitted() && $sspAssetUpdateForm->isValid()) {
            $sspAssetTransfer = $this->getFactory()->createSspAssetFormDataToTransferMapper()->mapFormDataToSspAssetTransfer(
                $sspAssetUpdateForm,
                $sspAssetUpdateForm->getData(),
            );

            $sspAssetCollectionResponseTransfer = $this->getClient()->updateSspAssetCollection(
                (new SspAssetCollectionRequestTransfer())
                    ->addSspAsset($sspAssetTransfer),
            );

            if (!$sspAssetCollectionResponseTransfer->getErrors()->count() && $sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_ASSET_UPDATED);

                return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_UPDATE, [
                    'reference' => $sspAssetCollectionResponseTransfer->getSspAssets()->offsetGet(0)->getReference(),
                ]);
            }

            foreach ($sspAssetCollectionResponseTransfer->getErrors() as $error) {
                $this->addErrorMessage($error->getMessageOrFail());
            }

            if (!$sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addErrorMessage(static::GLOSSARY_KEY_ASSET_UPDATE_ERROR);
            }
        }

        $currentCompanyBusinessUnitAssigment = null;
        foreach ($sspAssetTransfer->getAssignments() as $sspAssetAssignmentTransfer) {
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit() === $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit()) {
                $currentCompanyBusinessUnitAssigment = $sspAssetAssignmentTransfer;
            }
        }

        return $this->view(
            [
                'form' => $sspAssetUpdateForm->createView(),
                'sspAsset' => $sspAssetTransfer,
                'currentCompanyBusinessUnitAssigment' => $currentCompanyBusinessUnitAssigment,
            ],
            [],
            '@SspAssetManagement/views/update/update.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function listAction(Request $request): View
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('company.error.company_user_not_found');
        }

        if (!$this->getFactory()->createSspAssetCustomerPermissionChecker()->canViewAsset()) {
            throw new AccessDeniedHttpException('ssp_asset.access.denied');
        }

        $sspAssetSearchForm = $this->getFactory()->createSspAssetSearchForm(
            $this->getFactory()->createSspAssetSearchFormDataProvider()->getOptions(),
        );

        $data = $request->query->all()[SspAssetSearchForm::FORM_NAME] ?? [];
        $isReset = $data[SspAssetSearchForm::FIELD_RESET] ?? null;

        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();

        if (!$isReset) {
            $sspAssetSearchForm->handleRequest($request);

            $sspAssetCriteriaTransfer = $this->getFactory()->createSspAssetSearchFormHandler()->handleSearchForm($sspAssetSearchForm, $sspAssetCriteriaTransfer, $companyUserTransfer);
        }

        $sspAssetCollectionTransfer = $this->getFactory()
            ->createSspAssetReader()
            ->getSspAssetCollection($request, $sspAssetCriteriaTransfer, $companyUserTransfer);

        return $this->view(
            [
                'pagination' => $sspAssetCollectionTransfer->getPagination(),
                'sspAssetList' => $sspAssetCollectionTransfer->getSspAssets(),
                'sspAssetSearchForm' => $sspAssetSearchForm->createView(),
            ],
            [],
            '@SspAssetManagement/views/list/list.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateBusinessUnitRelationAction(Request $request): RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_OVERVIEW);
        }

        if (!$this->can(UnassignSspAssetPermissionPlugin::KEY)) {
            throw new AccessDeniedHttpException('ssp_asset.access.denied');
        }

        $form = $this->getFactory()->createSspAssetBusinessUnitRelationsForm([]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            /** @var \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer */
            $sspAssetTransfer = $this->getClient()->getSspAssetCollection(
                (new SspAssetCriteriaTransfer())->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())->addReference($formData[SspAssetBusinessUnitRelationsForm::FIELD_ASSET_REFERENCE]),
                ),
            )->getSspAssets()->getIterator()->current();

            if (!$sspAssetTransfer) {
                throw new NotFoundHttpException('ssp_asset.error.not_found');
            }

            $businessUnitIdToUnassign = (int)$formData[SspAssetBusinessUnitRelationsForm::FIELD_BUSINESS_UNIT_ID];

            if ($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnitOrFail() === $businessUnitIdToUnassign) {
                $this->addErrorMessage('ssp_asset.error.cannot_unassign_own_business_unit');

                return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetTransfer->getReference(),
                ]);
            }

            $sspAssetCollectionResponseTransfer = $this->getClient()->updateSspAssetCollection(
                (new SspAssetCollectionRequestTransfer())
                    ->addAssignmentToRemove(
                        (new SspAssetAssignmentTransfer())
                            ->setCompanyBusinessUnit(
                                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitIdToUnassign),
                            )
                            ->setSspAsset($sspAssetTransfer),
                    ),
            );

            if ($sspAssetCollectionResponseTransfer->getErrors()->count()) {
                foreach ($sspAssetCollectionResponseTransfer->getErrors() as $error) {
                    $this->addErrorMessage($error->getMessageOrFail());
                }

                return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetTransfer->getReference(),
                ]);
            }

            $this->addSuccessMessage(static::GLOSSARY_KEY_BUSINESS_UNIT_RELATION_UPDATED);

            return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_LIST);
        }

        return $this->redirectResponseInternal(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_LIST);
    }
}
