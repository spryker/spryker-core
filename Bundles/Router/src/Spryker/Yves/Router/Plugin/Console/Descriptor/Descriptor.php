<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Console\Descriptor;

use InvalidArgumentException;
use Spryker\Yves\Router\Route\Route;
use Symfony\Cmf\Component\Routing\ChainRouteCollection;
use Symfony\Component\Console\Descriptor\DescriptorInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouteCollection;

abstract class Descriptor implements DescriptorInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param object $object
     * @param array $options
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function describe(OutputInterface $output, $object, array $options = [])
    {
        $this->output = $output;

        switch (true) {
            case $object instanceof RouteCollection:
            case $object instanceof ChainRouteCollection:
                $this->describeRouteCollection($object, $options);
                break;
            case $object instanceof Route:
                $this->describeRoute($object, $options);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Object of type "%s" is not describable.', get_class($object)));
        }
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    protected function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $content
     * @param bool $decorated
     *
     * @return void
     */
    protected function write($content, $decorated = false)
    {
        $this->output->write($content, false, $decorated ? OutputInterface::OUTPUT_NORMAL : OutputInterface::OUTPUT_RAW);
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param array $options
     *
     * @return mixed
     */
    abstract protected function describeRouteCollection(RouteCollection $routes, array $options = []);

    /**
     * @param \Spryker\Yves\Router\Route\Route $route
     * @param array $options
     *
     * @return mixed
     */
    abstract protected function describeRoute(Route $route, array $options = []);

    /**
     * Formats a value as string.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function formatValue($value)
    {
        if (is_object($value)) {
            return sprintf('object(%s)', get_class($value));
        }

        if (is_string($value)) {
            return $value;
        }

        return preg_replace("/\n\s*/s", '', var_export($value, true));
    }
}
