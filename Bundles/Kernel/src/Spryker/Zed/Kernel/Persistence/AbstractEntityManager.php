<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;

abstract class AbstractEntityManager
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    private $factory;

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    )
    {
        $dependencyProvider->providePersistenceLayerDependencies($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectExternalDependencies(
        DependencyInjector $dependencyInjector,
        Container $container
    )
    {
        return $dependencyInjector->injectPersistenceLayerDependencies($container);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractPersistenceFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(TransferInterface $transfer)
    {
        //throw exception when child transfer entity is not from same module.
        //composite object should be handled in business with plugins.
        //when property is modified and is null, should remove item.
        $transferClassName = get_class($transfer);

        if (strpos($transferClassName, 'EntityTransfer') === 0) {
            throw new \Exception('Only entity transfer could be automatically mapped!');
        }

        $transferParts = explode('\\', $transferClassName);
        $entityName = str_replace('EntityTransfer', '', $transferParts[3]);

        $nameParts = preg_split('/(?=[A-Z])/', $entityName); //How to get module namespace from transfer namespace?

        $entityClassName = sprintf('\Orm\Zed\%s\Persistence\%s', $nameParts[2], $entityName);

        $transferArray = $transfer->modifiedToArray();

        $propelEntity = new $entityClassName;
        $propelEntity->fromArray($transferArray);

        /*foreach ($transferArray as $propertyName => $parentValue) {
            if (substr($propertyName, 0, 3) !== 'spy') {
                continue;
            }

            if (is_array($parentValue)) {
                $methodName = 'add' . $propertyName;
                foreach ($parentValue as $childValue) {
                    $propelEntity->addSpyBlogComment();
                }
            }
        }*/

        $propelEntity->save();

        $transfer = new $transferClassName;
        $transfer->fromArray($propelEntity->toArray(), true);

        return $transfer;
    }
}
