<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SelfServicePortalPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_POINT_WIDGET_CONTENT = 'customer/ssp-service-point-widget-content';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_POINT_SEARCH = 'customer/ssp-service-point-widget/search';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_LIST = 'customer/ssp-service/list';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_UPDATE_SERVICE_TIME = 'customer/ssp-service/update-service-time';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_CANCEL_SERVICE = 'customer/ssp-service/cancel-service';

    /**
     * @var string
     */
    protected const PATTERN_SSP_SERVICE_POINT_WIDGET_CONTENT = '/customer/ssp-service-point-widget-content';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SSP_COMPANY_FILE_DOWNLOAD = 'customer/ssp-file/download';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SSP_COMPANY_FILE_LIST_FILE = 'customer/ssp-file/list-file';

    /**
     * @var string
     */
    public const ROUTE_NAME_DASHBOARD_INDEX = 'customer/ssp-dashboard';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_INQUIRY = 'customer/ssp-inquiry/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_INQUIRY_LIST = 'customer/ssp-inquiry';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_INQUIRY_CANCEL = 'customer/ssp-inquiry/cancel';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_INQUIRY_DETAILS = 'customer/ssp-inquiry/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_INQUIRY_FILE_DOWNLOAD = '/customer/ssp-inquiry-file/download';

    /**
     * @var string
     */
    protected const ROUTE_SSP_ASSET_MANAGEMENT_WIDGET_CONTENT = 'customer/ssp-asset/widget-content';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_DETAILS = 'customer/ssp-asset/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_CREATE = 'customer/ssp-asset/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_UPDATE = 'customer/ssp-asset/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_LIST = 'customer/ssp-asset';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_VIEW_IMAGE = 'customer/ssp-asset/view-image';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_UPDATE_RELATIONS = 'customer/ssp-asset/update-relations';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_SEARCH = 'customer/ssp-asset/search';

    /**
     * @var string
     */
    protected const PATTERN_SSP_COMPANY_FILE_DOWNLOAD = '/customer/ssp-file/download';

    /**
     * @var string
     */
    protected const PATTERN_SSP_SERVICE_POINT_SEARCH = '/customer/ssp-service-point-widget/search';

    /**
     * @var string
     */
    protected const PATTERN_SSP_SERVICE_LIST = '/customer/ssp-service/list';

    /**
     * @var string
     */
    protected const PATTERN_SSP_SERVICE_UPDATE_SERVICE_TIME = '/customer/ssp-service/update-service-time';

    /**
     * @var string
     */
    protected const PATTERN_SSP_SERVICE_CANCEL_SERVICE = '/customer/ssp-service/cancel-service';

    /**
     * @var string
     */
    protected const PATTERN_SSP_COMPANY_FILE_LIST_FILE = '/customer/ssp-file/list-file';

    /**
     * @var string
     */
    protected const REFERENCE_REGEX = '[a-zA-Z0-9-_]+';

    /**
     * @var string
     */
    protected const PARAM_SSP_INQUIRY_REFERENCE = 'sspInquiryReference';

    /**
     * {@inheritDoc}
     * - Adds routes to the route collection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSspServicePointWidgetContentRoute($routeCollection);
        $routeCollection = $this->addSspServicePointSearchRoute($routeCollection);
        $routeCollection = $this->addSspServiceListRoute($routeCollection);
        $routeCollection = $this->addSspServiceUpdateServiceTimeRoute($routeCollection);
        $routeCollection = $this->addSspServiceCancelServiceRoute($routeCollection);
        $routeCollection = $this->addSspCompanyFileListFileRoute($routeCollection);
        $routeCollection = $this->addSspCompanyFileDownloadRoute($routeCollection);
        $routeCollection = $this->addCustomerDashboardRoute($routeCollection);
        $routeCollection = $this->addCustomerSspInquiryCreateRoute($routeCollection);
        $routeCollection = $this->addCustomerSspInquiryDetailsRoute($routeCollection);
        $routeCollection = $this->addSspInquiryCancelRoute($routeCollection);
        $routeCollection = $this->addCustomerSspInquiryListRoute($routeCollection);
        $routeCollection = $this->addDownloadFileRoute($routeCollection);
        $routeCollection = $this->addAssetWidgetContentRoute($routeCollection);
        $routeCollection = $this->addAssetDetailsRoute($routeCollection);
        $routeCollection = $this->addAssetCreateRoute($routeCollection);
        $routeCollection = $this->addAssetUpdateRoute($routeCollection);
        $routeCollection = $this->addViewAssetImageRoute($routeCollection);
        $routeCollection = $this->addAssetListRoute($routeCollection);
        $routeCollection = $this->addUnassignBusinessUnitRoute($routeCollection);
        $routeCollection = $this->addAssetSearchRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ServicePointWidgetContentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServicePointWidgetContentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_SSP_SERVICE_POINT_WIDGET_CONTENT,
            'SelfServicePortal',
            'ServicePointWidgetContent',
        );

        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_POINT_WIDGET_CONTENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ServicePointSearchController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServicePointSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_SSP_SERVICE_POINT_SEARCH,
            'SelfServicePortal',
            'ServicePointSearch',
        );
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_POINT_SEARCH, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ListServiceController::listAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_SSP_SERVICE_LIST,
            'SelfServicePortal',
            'ListService',
            'listAction',
        );
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_LIST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\UpdateServiceTimeController::updateServiceTimeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceUpdateServiceTimeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_SSP_SERVICE_UPDATE_SERVICE_TIME,
            'SelfServicePortal',
            'UpdateServiceTime',
            'updateServiceTimeAction',
        );
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_UPDATE_SERVICE_TIME, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\CancelServiceController::cancelServiceAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceCancelServiceRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_SSP_SERVICE_CANCEL_SERVICE,
            'SelfServicePortal',
            'CancelService',
            'cancelServiceAction',
        );
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_CANCEL_SERVICE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ListCompanyFileController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspCompanyFileListFileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::PATTERN_SSP_COMPANY_FILE_LIST_FILE, 'SelfServicePortal', 'ListCompanyFile', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_SSP_COMPANY_FILE_LIST_FILE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\DownloadCompanyFileController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspCompanyFileDownloadRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(static::PATTERN_SSP_COMPANY_FILE_DOWNLOAD, 'SelfServicePortal', 'DownloadCompanyFile', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_SSP_COMPANY_FILE_DOWNLOAD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\DashboardController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerDashboardRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-dashboard', 'SelfServicePortal', 'Dashboard');

        $routeCollection->add(static::ROUTE_NAME_DASHBOARD_INDEX, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ListInquiryController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry', 'SelfServicePortal', 'ListInquiry', 'indexAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_LIST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\InquiryController::detailAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry/details', 'SelfServicePortal', 'Inquiry', 'detailAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\InquiryController::cancelAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspInquiryCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/ssp-inquiry/cancel/{sspInquiryReference}', 'SelfServicePortal', 'Inquiry', 'cancelAction');
        $route = $route->setRequirement(static::PARAM_SSP_INQUIRY_REFERENCE, static::REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\InquiryController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/ssp-inquiry/create', 'SelfServicePortal', 'Inquiry', 'createAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\DownloadInquiryFileController::downloadAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDownloadFileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry-file/download', 'SelfServicePortal', 'DownloadInquiryFile', 'downloadAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_FILE_DOWNLOAD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetWidgetContentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetWidgetContentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/ssp-asset/widget-content', 'SelfServicePortal', 'AssetWidgetContent', 'indexAction');
        $routeCollection->add(static::ROUTE_SSP_ASSET_MANAGEMENT_WIDGET_CONTENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetController::detailsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset/details', 'SelfServicePortal', 'Asset', 'detailsAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset/create', 'SelfServicePortal', 'Asset', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetController::updateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset/update', 'SelfServicePortal', 'Asset', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\DownloadAssetImageController::viewImageAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addViewAssetImageRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset/view-image', 'SelfServicePortal', 'DownloadAssetImage', 'viewImageAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_VIEW_IMAGE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\ListAssetController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset', 'SelfServicePortal', 'ListAsset', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_LIST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetController::updateBusinessUnitRelationAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUnassignBusinessUnitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('customer/ssp-asset/update-relations', 'SelfServicePortal', 'Asset', 'updateBusinessUnitRelationAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_UPDATE_RELATIONS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SelfServicePortal\Controller\AssetController::searchAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/ssp-asset/search', 'SelfServicePortal', 'Asset', 'searchAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_SEARCH, $route);

        return $routeCollection;
    }
}
