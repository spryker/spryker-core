<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Pyz\Yves\Application\Plugin\Pimple;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * @return \Generated\Client\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()['form.factory'];
    }

}
