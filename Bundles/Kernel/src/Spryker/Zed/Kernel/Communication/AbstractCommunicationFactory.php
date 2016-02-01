<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

abstract class AbstractCommunicationFactory extends AbstractFactory
{

    const FORM_FACTORY = 'form.factory';

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->provideCommunicationLayerDependencies($container);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()[self::FORM_FACTORY];
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $formTypeInterface
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(FormTypeInterface $formTypeInterface, array $options = [])
    {
        $form = $this->getFormFactory()
            ->create($formTypeInterface, $formTypeInterface->populateFormFields(), $options);

        return $form;
    }

}
