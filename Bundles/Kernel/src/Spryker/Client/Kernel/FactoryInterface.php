<?php

namespace Spryker\Client\Kernel;

interface FactoryInterface
{

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container);

}
