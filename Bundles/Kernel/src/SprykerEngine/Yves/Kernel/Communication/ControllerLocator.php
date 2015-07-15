<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\ClassNamePattern;

class ControllerLocator implements ControllerLocatorInterface
{

    /**
     * @var BundleControllerActionInterface
     */
    private $bundleControllerAction;

    /**
     * @var string
     */
    private $classNamePattern;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param string $classNamePattern
     */
    public function __construct(
        BundleControllerActionInterface $bundleControllerAction,
        $classNamePattern = ClassNamePattern::CONTROLLER
    ) {
        $this->bundleControllerAction = $bundleControllerAction;
        $this->classNamePattern = $classNamePattern;
    }

    /**
     * @param \Pimple $application
     * @param LocatorLocatorInterface $locator
     *
     * @throws ClassResolver\ClassNotFoundException
     *
     * @return object
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator)
    {
        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
        $factory = new Factory($this->bundleControllerAction->getBundle());

        return $resolver->resolve(
            $this->prepareClassName(),
            $this->bundleControllerAction->getBundle(),
            [$application, $factory, $locator]
        );
    }

    /**
     * @return bool
     */
    public function canLocate()
    {
        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());

        return $resolver->canResolve(
            $this->prepareClassName(),
            $this->bundleControllerAction->getBundle()
        );
    }

    /**
     * @return mixed
     */
    private function prepareClassName()
    {
        return str_replace('{{controller}}', $this->bundleControllerAction->getController(), $this->classNamePattern);
    }

}
