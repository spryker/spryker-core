<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Orm\Zed\Blog\Persistence\SpyBlogComment;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Propel\Runtime\Map\TableMap;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\UnderscoreToCamelCase;

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
     * @todo cleanup this mess
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(TransferInterface $transfer)
    {
        //throw exception when child transfer entity is not from same module.
        //composite object should be handled in business with plugins.
        //when property is modified and is null, should remove item.

        //how to get current module?
        $moduleName = 'Blog';

        $transferClassName = get_class($transfer);
        if (strpos($transferClassName, 'EntityTransfer') === 0) {
            throw new \Exception('Only entity transfer could be automatically mapped!');
        }

        if (strpos($transferClassName, 'BlogComment') !== false) {
            $entityClassName = sprintf('\Orm\Zed\%s\Persistence\%s', $moduleName, 'SpyBlogComment');
        } else {
            $entityClassName = sprintf('\Orm\Zed\%s\Persistence\%s', $moduleName, 'SpyBlog');
        }

        //find entity class name

        $transferArray = $transfer->modifiedToArray();

        $propelEntity = new $entityClassName;
        $propelEntity->fromArray($transferArray);

        foreach ($transferArray as $property => $value) {
            if (substr($property, 0, 4) !== 'spy_') {
                continue;
            }

            if (!$transfer->isPropertyInModule($moduleName, $property)) {
                throw new \Exception(
                    sprintf(
                        'Property "%s" is not allowed to be saved in module %s',
                        $property,
                        $moduleName
                    )
                );
            }

            if (is_array($value)) {

                foreach ($value as $childData) {

                    $entityName = (new FilterChain())
                        ->attach(new UnderscoreToCamelCase())
                        ->filter($property);

                    //find a way to get singular value
                    $entityName = substr($entityName, 0, -1);

                    $entityClassName = sprintf('\Orm\Zed\%s\Persistence\%s', $moduleName, $entityName);

                    $childEntity = new $entityClassName();
                    $childEntity->fromArray($childData);
                    $methodName = 'add'. $entityName;
                    $propelEntity->$methodName($childEntity);

                }
            }  else {
                //$childEntity->fromArray($value);
                //$childEntity->save();
            }

        }

        $propelEntity->save();

        $transfer = new $transferClassName;
        $transfer->fromArray($propelEntity->toArray(TableMap::TYPE_FIELDNAME, true, [], true), true);

        return $transfer;
    }
}
