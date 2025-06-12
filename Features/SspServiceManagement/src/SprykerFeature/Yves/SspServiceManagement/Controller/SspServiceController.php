<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Yves\SspServiceManagement\Form\SspServiceCancelForm;
use SprykerFeature\Yves\SspServiceManagement\Plugin\Router\SspServiceManagementPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SspServiceManagement\SspServiceManagementClientInterface getClient()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class SspServiceController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CANCELLATION_ERROR = 'ssp_service_management.cancellation.error';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CANCELLATION_SUCCESS = 'ssp_service_management.cancellation.success';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CANCELLATION_ERROR_NO_ITEMS = 'ssp_service_management.cancellation.error.no_items';

    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';

    /**
     * @var string
     */
    protected const PARAM_SORT_FIELD = 'sort';

    /**
     * @var string
     */
    protected const PARAM_SORT_DIRECTION = 'direction';

    /**
     * @var string
     */
    protected const PARAM_SEARCH = 'search';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_ASC = 'ASC';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_DESC = 'DESC';

    /**
     * @var int
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @var string
     */
    protected const PARAM_ITEM_UUID = 'item-uuid';

    /**
     * @var string
     */
    protected const PARAM_ORDER_ID = 'id-sales-order';

    /**
     * @var string
     */
    protected const QUERY_PARAM_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function listAction(Request $request): View
    {
        $viewData = $this->executeListAction($request);

        return $this->view(
            $viewData,
            [],
            '@SspServiceManagement/views/list/list.twig',
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

        $sspServiceCollectionTransfer = $this->getClient()->getServiceCollection($sspServiceCriteriaTransfer);

        return [
            'pagination' => $sspServiceCollectionTransfer->getPagination(),
            'serviceList' => $sspServiceCollectionTransfer->getServices(),
            'serviceSearchForm' => $serviceSearchForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $serviceSearchForm
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
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
                $this->getFactory()->getConfig()->getProductServiceTypeName(),
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    protected function setPagination(
        Request $request,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setMaxPerPage(static::DEFAULT_ITEMS_PER_PAGE);
        $paginationTransfer->setPage((int)$request->query->get(static::PARAM_PAGE, 1));

        return $sspServiceCriteriaTransfer->setPagination($paginationTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateServiceTimeAction(Request $request): View|RedirectResponse
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            throw new NotFoundHttpException();
        }

        $orderItemUuid = $request->get(static::PARAM_ITEM_UUID);
        $idSalesOrder = $request->get(static::PARAM_ORDER_ID);

        $itemTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderItem((int)$idSalesOrder, $orderItemUuid);

        $serviceItemSchedulerForm = $this->getFactory()->createServiceItemSchedulerForm($itemTransfer);
        $serviceItemSchedulerForm->handleRequest($request);

        if ($serviceItemSchedulerForm->isSubmitted() && $serviceItemSchedulerForm->isValid()) {
            $itemTransfer = $serviceItemSchedulerForm->getData();

            $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
            $salesOrderItemCollectionRequestTransfer->addItem($itemTransfer);

            $salesOrderItemCollectionResponseTransfer = $this->getClient()
                ->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

            if ($salesOrderItemCollectionResponseTransfer->getErrors()->count() === 0) {
                $this->addSuccessMessage('ssp_service_management.update_scheduled_time.success');

                return $this->redirectResponseInternal(SspServiceManagementPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_SERVICE_LIST);
            }
        }

        return $this->view(
            [
                'orderItem' => $itemTransfer,
                'serviceItemSchedulerForm' => $serviceItemSchedulerForm->createView(),
            ],
            [],
            '@SspServiceManagement/views/update-service-time/update-service-time.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelServiceAction(Request $request): RedirectResponse
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            throw new NotFoundHttpException();
        }

        $sspServiceCancelForm = $this->getFactory()->getSspServiceCancelForm();
        $sspServiceCancelForm->handleRequest($request);

        $response = $this->redirectResponseInternal(SspServiceManagementPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_SERVICE_LIST);

        if (!$sspServiceCancelForm->isSubmitted() || !$sspServiceCancelForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CANCELLATION_ERROR);

            return $response;
        }

        $formData = $sspServiceCancelForm->getData();
        $orderItemUuid = $formData[SspServiceCancelForm::FIELD_ITEM_UUID];
        $idSalesOrder = $formData[SspServiceCancelForm::FIELD_ID_SALES_ORDER];

        $itemTransfer = $this->getFactory()
            ->createOrderReader()
            ->getOrderItem((int)$idSalesOrder, $orderItemUuid);

        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->addItem($itemTransfer);

        $salesOrderItemCollectionResponseTransfer = $this->getClient()
            ->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        if ($salesOrderItemCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CANCELLATION_ERROR);

            return $response;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CANCELLATION_SUCCESS);

        return $response;
    }
}
