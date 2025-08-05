<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ListServiceController extends AbstractController
{
    /**
     * @var string
     */
    protected const QUERY_PARAM_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    public function listAction(Request $request): View
    {
        $viewData = $this->executeListAction($request);

        return $this->view(
            $viewData,
            [],
            '@SelfServicePortal/views/list-service/list-service.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    protected function executeListAction(Request $request): array
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            throw new NotFoundHttpException();
        }

        $sspServiceCriteriaTransfer = new SspServiceCriteriaTransfer();
        $serviceSearchForm = $this->getFactory()->getServiceSearchForm();

        $sspServiceCriteriaTransfer = $this->handleServiceSearchFormSubmit($request, $serviceSearchForm, $sspServiceCriteriaTransfer);
        $sspServiceCriteriaTransfer = $this->setPagination($request, $sspServiceCriteriaTransfer);

        if ($request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE)) {
            if (!$sspServiceCriteriaTransfer->getServiceConditions()) {
                $sspServiceCriteriaTransfer->setServiceConditions(new SspServiceConditionsTransfer());
            }
            $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->addSspAssetReference((string)$request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE));
        }

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer || !$companyUserTransfer->getIdCompanyUser()) {
            throw new NotFoundHttpException('Company user not found.');
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomerById($companyUserTransfer->getFkCustomerOrFail());
        $companyUserTransfer->setCustomer($customerTransfer);
        $sspServiceCriteriaTransfer->setCompanyUser($companyUserTransfer);

        $sspServiceCollectionTransfer = $this->getClient()->getSspServiceCollection($sspServiceCriteriaTransfer);

        return [
            'pagination' => $sspServiceCollectionTransfer->getPagination(),
            'serviceList' => $sspServiceCollectionTransfer->getServices(),
            'serviceSearchForm' => $serviceSearchForm->createView(),
        ];
    }

    protected function handleServiceSearchFormSubmit(
        Request $request,
        FormInterface $serviceSearchForm,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $serviceSearchForm->handleRequest($request);

        return $this->getFactory()
            ->createServiceSearchFormHandler()
            ->handleServiceSearchFormSubmit(
                $serviceSearchForm,
                $sspServiceCriteriaTransfer,
            );
    }

    protected function setPagination(
        Request $request,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setMaxPerPage($this->getFactory()->getConfig()->getServiceListDefaultItemsPerPage());
        $paginationTransfer->setPage((int)$request->query->get($this->getFactory()->getConfig()->getServiceListPageParameterName(), 1));

        return $sspServiceCriteriaTransfer->setPagination($paginationTransfer);
    }
}
