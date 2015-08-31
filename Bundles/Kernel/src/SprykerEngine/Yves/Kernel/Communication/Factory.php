<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractFactory;
use SprykerEngine\Shared\Kernel\Factory2;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\\\{{bundle}}{{store}}\\Communication\\';

    /**
     * @var string
     */
    protected $application = 'Yves';

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

        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle() . $class;
        }

        return Factory2::getInstance()->create($this->application, $this->getBundle(), $class, $this->layer, $arguments);
//
//        $class = $this->buildClassName($class);
//        $resolver = $this->getResolver();
//
//        return $resolver->resolve($class, $this->getBundle(), $arguments);
    }

}
