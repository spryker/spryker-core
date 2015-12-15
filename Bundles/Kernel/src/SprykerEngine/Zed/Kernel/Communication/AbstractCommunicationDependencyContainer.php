<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use Spryker\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

abstract class AbstractCommunicationDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    const FORM_FACTORY = 'form.factory';

    /**
     * @param AbstractBundleDependencyProvider $dependencyProvider
     * @param Container $container
     *
     * @return Container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->provideCommunicationLayerDependencies($container);
    }

    /**
     * @return FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(self::FORM_FACTORY);
    }

    /**
     * @param FormTypeInterface $formTypeInterface
     * @param array $options
     *
     * @return FormInterface
     */
    protected function createForm(FormTypeInterface $formTypeInterface, array $options = [])
    {
        $form = $this->getFormFactory()
            ->create($formTypeInterface, $formTypeInterface->populateFormFields(), $options);

        return $form;
    }

}
