<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Yves\\{{bundle}}{{store}}\\Communication\\';

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

        $class = $this->buildClassName($class);
        $resolver = $this->getResolver();

        return $resolver->resolve($class, $this->getBundle(), $arguments);
    }

}
