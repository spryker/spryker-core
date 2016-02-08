<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
     * @throws \Spryker\Zed\Graph\Communication\Exception\GraphAdapterNameIsAnObjectException
     * @throws \Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterNameException
     * @throws \Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterException
     *
     * @return \Spryker\Shared\Graph\GraphAdapterInterface
     */
    protected function createAdapter()
    {
        $adapterName = $this->getConfig()->getGraphAdapterName();

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

        $adapter = new $adapterName();

        if (!($adapter instanceof GraphAdapterInterface)) {
            $message = sprintf(
                'Provided "%s" must be an instanceof "%s"',
                get_class($adapter),
                GraphAdapterInterface::class
            );
            throw new InvalidGraphAdapterException($message);
        }

        return $adapter;
    }

}
