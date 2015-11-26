<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

abstract class AbstractCommunicationDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    const FORM_FACTORY = 'form.factory';

    /**
     * External dependencies
     *
     * @var Container
     */
    private $container;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if ($this->container === null) {
            throw new \ErrorException('Container does not exist in ' . get_class($this));
        }

        if ($this->container->offsetExists($key) === false) {
            throw new \ErrorException('Key ' . $key . ' does not exist in container: ' . get_class($this));
        }

        return $this->container[$key];
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     */
    public function setQueryContainer($queryContainer)
    {
        $this->queryContainer = $queryContainer;
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
            ->create($formTypeInterface, $formTypeInterface->populateFormFields(), $options)
        ;

        return $form;
    }
}
