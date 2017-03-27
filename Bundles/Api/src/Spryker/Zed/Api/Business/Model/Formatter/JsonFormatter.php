<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Formatter;

use Silex\Application;
use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Spryker\Zed\Kernel\Communication\Controller\RouteNameResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class JsonFormatter implements FormatterInterface
{

    /**
     * @var UtilEncodingService
     */
    protected $service;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingService $service
     */
    public function __construct(UtilEncodingService $service)
    {
        $this->service = $service;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function format($value)
    {
        $options = Json::DEFAULT_OPTIONS | JSON_PRETTY_PRINT;

        return $this->service->encodeJson($value, $options);
    }

}
