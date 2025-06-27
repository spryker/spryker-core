<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\CreateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\UnassignSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\UpdateSspAssetPermissionPlugin;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetBusinessUnitRelationsForm;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetSearchForm;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class AssetController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_CREATED = 'self_service_portal.asset.success.created';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_UPDATED = 'self_service_portal.asset.success.updated';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_CREATE_ERROR = 'self_service_portal.asset.error.create';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_UPDATE_ERROR = 'self_service_portal.asset.error.update';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_BUSINESS_UNIT_RELATION_UPDATED = 'self_service_portal.asset.success.business_unit_relation_updated';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_CUSTOMER_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * The number of services to be included in the SspAssetTransfer.
     * This constant is used to limit the number of services fetched for each asset.
     *
     * @var int
     */
    protected const SERVICE_COUNT = 4;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request): View|RedirectResponse
    {
        $sspAssetReference = (string)$request->query->get('reference');
        if (!$sspAssetReference) {
            throw new NotFoundHttpException('Asset reference not found');
        }

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$this->getFactory()->createSspAssetCustomerPermissionChecker()->canViewAsset()) {
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
        }

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException('Company user not found');
        }

        $companyUserTransfer->setCustomer($this->getFactory()->getCustomerClient()->getCustomerById($companyUserTransfer->getFkCustomerOrFail()));

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions(
                (new SspAssetConditionsTransfer())
                    ->addReference($sspAssetReference)
                    ->setStatuses(
                        $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_VIEW),
                    ),
            )
            ->setInclude(
                (new SspAssetIncludeTransfer())
                    ->setWithOwnerCompanyBusinessUnit(true)
                    ->setWithAssignedBusinessUnits(true)
                    ->setWithSspInquiries(true)
                    ->setWithFiles(true)
                    ->setWithServicesCount(static::SERVICE_COUNT),
            );

        $sspAssetCriteriaTransfer->setCompanyUser($companyUserTransfer);

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

        $canBusinessUnitBeUnassigned = false;
        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspAssetBusinessUnitAssignmentTransfer) {
            if ($sspAssetBusinessUnitAssignmentTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail() === $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit()) {
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
                'isUnassignmentAllowed' => in_array(
                    $sspAssetTransfer->getStatusOrFail(),
                    $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_UNASSIGN),
                ),
                'isUpdateAllowed' => in_array(
                    $sspAssetTransfer->getStatusOrFail(),
                    $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_UPDATE),
                )],
            [],
            '@SelfServicePortal/views/asset-details/asset-details.twig',
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
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
        }

        $sspAssetCreateForm = $this->getFactory()
            ->createAssetForm()
            ->handleRequest($request);

        if ($sspAssetCreateForm->isSubmitted() && $sspAssetCreateForm->isValid()) {
            $sspAssetTransfer = $this->getFactory()->createSspAssetFormDataToTransferMapper()->mapFormDataToSspAssetTransfer(
                $sspAssetCreateForm,
                $sspAssetCreateForm->getData(),
            );

            $sspAssetTransfer->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())->setCompanyBusinessUnit(
                    $companyUserTransfer->getCompanyBusinessUnit(),
                ),
            );

            $sspAssetTransfer->setCompanyBusinessUnit($companyUserTransfer->getCompanyBusinessUnit());

            $sspAssetCollectionResponseTransfer = $this->getClient()->createSspAssetCollection(
                (new SspAssetCollectionRequestTransfer())
                    ->addSspAsset($sspAssetTransfer)
                    ->setCompanyUser($companyUserTransfer),
            );

            if (!$sspAssetCollectionResponseTransfer->getErrors()->count() && $sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_ASSET_CREATED);

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
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
            '@SelfServicePortal/views/asset-create/asset-create.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request): View|RedirectResponse
    {
        $sspAssetReference = $request->get('reference');

        if (!$sspAssetReference) {
            throw new NotFoundHttpException('Asset reference not found');
        }

        if (!$this->can(UpdateSspAssetPermissionPlugin::KEY)) {
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
        }

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if (!$companyUserTransfer) {
            $this->addErrorMessage('company.error.company_user_not_found');

            return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_OVERVIEW);
        }

        $sspAssetFormDataProvider = $this->getFactory()->createSspAssetFormDataProvider();
        $sspAssetTransfer = $sspAssetFormDataProvider->getData($sspAssetReference, $companyUserTransfer);

        if (!$sspAssetTransfer) {
            throw new NotFoundHttpException('ssp_asset.error.not_found');
        }

        if (
            !in_array(
                $sspAssetTransfer->getStatusOrFail(),
                $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_UPDATE),
            )
        ) {
            throw new AccessDeniedHttpException('self_service_portal.asset.access.status.restricted');
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
                (new SspAssetCollectionRequestTransfer())->setCompanyUser($companyUserTransfer)
                    ->addSspAsset($sspAssetTransfer),
            );

            if (!$sspAssetCollectionResponseTransfer->getErrors()->count() && $sspAssetCollectionResponseTransfer->getSspAssets()->count()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_ASSET_UPDATED);

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current()->getReference(),
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
        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspBusinessUnitAssetAssignmentTransfer) {
            if ($sspBusinessUnitAssetAssignmentTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail() === $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit()) {
                $currentCompanyBusinessUnitAssigment = $sspBusinessUnitAssetAssignmentTransfer;
            }
        }

        return $this->view(
            [
                'form' => $sspAssetUpdateForm->createView(),
                'sspAsset' => $sspAssetTransfer,
                'currentCompanyBusinessUnitAssigment' => $currentCompanyBusinessUnitAssigment,
            ],
            [],
            '@SelfServicePortal/views/asset-update/asset-update.twig',
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
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
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
            '@SelfServicePortal/views/list-asset/list-asset.twig',
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
            throw new AccessDeniedHttpException('self_service_portal.asset.access.denied');
        }

        $form = $this->getFactory()->createSspAssetBusinessUnitRelationsForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            /** @var \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer */
            $sspAssetTransfer = $this->getClient()->getSspAssetCollection(
                (new SspAssetCriteriaTransfer())->setCompanyUser($companyUserTransfer)
                    ->setSspAssetConditions(
                        (new SspAssetConditionsTransfer())->addReference($formData[SspAssetBusinessUnitRelationsForm::FIELD_ASSET_REFERENCE]),
                    ),
            )->getSspAssets()->getIterator()->current();

            if (!$sspAssetTransfer) {
                throw new NotFoundHttpException('self_service_portal.asset.error.not_found');
            }

            if (!in_array($sspAssetTransfer->getStatusOrFail(), $this->getStatusesByAllowedAction(SelfServicePortalConfig::ASSET_ACTION_UNASSIGN))) {
                throw new AccessDeniedHttpException('self_service_portal.asset.access.status.restricted');
            }

            $businessUnitIdToUnassign = (int)$formData[SspAssetBusinessUnitRelationsForm::FIELD_BUSINESS_UNIT_ID];

            if ($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnitOrFail() === $businessUnitIdToUnassign) {
                $this->addErrorMessage('self_service_portal.asset.error.cannot_unassign_own_business_unit');

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetTransfer->getReference(),
                ]);
            }

            $sspAssetCollectionResponseTransfer = $this->getClient()->updateSspAssetCollection(
                (new SspAssetCollectionRequestTransfer())
                    ->addBusinessUnitAssignmentToDelete(
                        (new SspAssetBusinessUnitAssignmentTransfer())
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

                return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_DETAILS, [
                    'reference' => $sspAssetTransfer->getReference(),
                ]);
            }

            $this->addSuccessMessage(static::GLOSSARY_KEY_BUSINESS_UNIT_RELATION_UPDATED);

            return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_LIST);
        }

        return $this->redirectResponseInternal(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_LIST);
    }

    /**
     * @param string $allowedAction
     *
     * @return array<string>
     */
    protected function getStatusesByAllowedAction(string $allowedAction): array
    {
        $statuses = [];

        foreach ($this->getFactory()->getConfig()->getSspStatusAllowedActionsMapping() as $status => $allowedActions) {
            if (in_array($allowedAction, $allowedActions)) {
                $statuses[] = $status;
            }
        }

        return $statuses;
    }
}
