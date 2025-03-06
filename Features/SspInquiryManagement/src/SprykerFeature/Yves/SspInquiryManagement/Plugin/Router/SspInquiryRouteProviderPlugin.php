<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SspInquiryRouteProviderPlugin extends AbstractRouteProviderPlugin
{
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
    protected const REFERENCE_REGEX = '[a-zA-Z0-9-_]+';

    /**
     * @var string
     */
    protected const PARAM_SSP_INQUIRY_REFERENCE = 'sspInquiryReference';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $this->addCustomerSspInquiryCreateRoute($routeCollection);
        $this->addCustomerSspInquiryDetailsRoute($routeCollection);
        $this->addSspInquiryCancelRoute($routeCollection);
        $this->addCustomerSspInquiryListRoute($routeCollection);
        $this->addDownloadFileRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry', 'SspInquiryManagement', 'SspInquiry', 'listAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_LIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry/details', 'SspInquiryManagement', 'SspInquiry', 'detailAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspInquiryCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/ssp-inquiry/cancel/{sspInquiryReference}', 'SspInquiryManagement', 'SspInquiry', 'cancelAction');
        $route = $route->setRequirement(static::PARAM_SSP_INQUIRY_REFERENCE, static::REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCustomerSspInquiryCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/customer/ssp-inquiry/create', 'SspInquiryManagement', 'SspInquiry', 'createAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDownloadFileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/customer/ssp-inquiry-file/download', 'SspInquiryManagement', 'SspInquiryFile', 'downloadAction');

        $routeCollection->add(static::ROUTE_NAME_SSP_INQUIRY_FILE_DOWNLOAD, $route);

        return $routeCollection;
    }
}
