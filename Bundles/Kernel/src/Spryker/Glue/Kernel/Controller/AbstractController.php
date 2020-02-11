<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Controller;

use Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController
{
    /**
     * @var \Silex\Application|\Spryker\Service\Container\ContainerInterface
     */
    private $application;

    /**
     * @var \Spryker\Glue\Kernel\AbstractFactory
     */
    private $factory;

    /**
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * @param \Silex\Application|\Spryker\Service\Container\ContainerInterface $application
     *
     * @return $this
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param array|null $data
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function jsonResponse($data = null, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function viewResponse(array $data = [])
    {
        return $data;
    }

    /**
     * @return \Silex\Application|\Spryker\Service\Container\ContainerInterface
     */
    protected function getApplication()
    {
        return $this->application;
    }
}
