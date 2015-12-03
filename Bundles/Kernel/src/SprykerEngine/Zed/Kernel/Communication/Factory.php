<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractFactory;
use SprykerEngine\Zed\Kernel\BundleConfigLocator;
use SprykerEngine\Shared\Kernel\ClassMapFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @var string
     */
    protected $layer = 'Communication';

    /**
     * @param string $class
     *
     * @throws \Exception
     *
     * @return object
     */
    public function create($class)
    {
        $arguments = func_get_args();

        array_shift($arguments);

        if ($this->isMagicCall) {
            $arguments = (count($arguments) > 0) ? $arguments[0] : [];
        }
        $this->isMagicCall = false;

        return ClassMapFactory::getInstance()->create('Zed', $this->getBundle(), $class, 'Communication', $arguments);
    }

}
