<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\ClassMapFactory;

/**
 * @deprecated Use "new" for instance creation, do not use Factory anymore. This will be removed soon
 */
class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @var string
     */
    protected $layer = 'Persistence';

    /**
     * @var array
     */
    protected $baseClasses = [
        'DependencyContainer',
        'QueryContainer',
    ];

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

        return ClassMapFactory::getInstance()->create('Zed', $this->getBundle(), $class, 'Persistence', $arguments);
    }

}
