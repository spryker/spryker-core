<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Client\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Client\ZedRequest\Stub\BaseStub;
use Spryker\Shared\ZedRequest\Client\Message;

abstract class AbstractClient
{

    /**
     * @var AbstractFactory
     */
    private $factory;

    /**
     * @return AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws FactoryNotFoundException
     *
     * @return AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return BaseStub
     */
    protected function getZedStub()
    {
        $factory = $this->getFactory();
        if (!method_exists($factory, 'createZedStub')) {
            throw new \BadMethodCallException(
                sprintf('createZedStub method is not implemented in "%s".', get_class($factory))
            );
        }

        return $this->getFactory()->createZedStub();
    }

    /**
     * @return Message[]
     */
    public function getZedInfoMessages()
    {
        return $this->getZedStub()->getInfoMessages();
    }

    /**
     * @return Message[]
     */
    public function getZedSuccessMessages()
    {
        return $this->getZedStub()->getSuccessMessages();
    }

    /***
     * @return Message[]
     */
    public function getZedErrorMessages()
    {
        return $this->getZedStub()->getErrorMessages();
    }

}
