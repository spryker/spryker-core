<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\ClassMapFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $application = 'Yves';

    /**
     * @var string
     */
    protected $layer = null;

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

        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle() . $class;
        }

        return ClassMapFactory::getInstance()->create($this->application, $this->getBundle(), $class, $this->layer, $arguments);
    }

}
