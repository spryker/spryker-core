<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Graph\Communication;

use Spryker\Shared\Graph\Graph;
use Spryker\Shared\Graph\GraphAdapterInterface;
use Spryker\Zed\Graph\Communication\Exception\GraphAdapterNameIsAnObjectException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterNameException;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Graph\GraphConfig getConfig()
 */
class GraphCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    public function createGraph($name, array $attributes = [], $directed = true, $strict = true)
    {
        $graph = new Graph($this->createAdapter(), $name, $attributes, $directed, $strict);

        return $graph;
    }

    /**
     * @return \Spryker\Shared\Graph\GraphAdapterInterface
     */
    protected function createAdapter()
    {
        $adapterName = $this->getConfig()->getGraphAdapterName();
        $this->validateAdapterName($adapterName);

        $adapter = new $adapterName();
        $this->validateAdapterClass($adapter);

        return $adapter;
    }

    /**
     * @param string $adapterName
     *
     * @throws \Spryker\Zed\Graph\Communication\Exception\GraphAdapterNameIsAnObjectException
     * @throws \Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterNameException
     *
     * @return void
     */
    protected function validateAdapterName($adapterName)
    {
        if (is_object($adapterName)) {
            throw new GraphAdapterNameIsAnObjectException(
                'Your config returned an object instance, this is not allowed.'
            );
        }

        if (!class_exists($adapterName)) {
            throw new InvalidGraphAdapterNameException(
                sprintf('Invalid GraphAdapterName provided. "%s" can not be instanced.', $adapterName)
            );
        }
    }

    /**
     * @param string $adapter
     *
     * @throws \Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterException
     *
     * @return void
     */
    protected function validateAdapterClass($adapter)
    {
        if (!($adapter instanceof GraphAdapterInterface)) {
            $message = sprintf(
                'Provided "%s" must be an instanceof "%s"',
                get_class($adapter),
                GraphAdapterInterface::class
            );

            throw new InvalidGraphAdapterException($message);
        }
    }
}
