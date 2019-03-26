<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfiler\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Symfony\Component\Form\Extension\DataCollector\FormDataCollector;
use Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Symfony\Component\Form\Extension\DataCollector\FormDataExtractor;
use Symfony\Component\Form\Extension\DataCollector\FormDataExtractorInterface;
use Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeFactoryDataCollectorProxy;
use Symfony\Component\Form\Extension\DataCollector\Type\DataCollectorTypeExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;

class WebProfilerFormPlugin implements FormPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds `ResolvedTypeFactoryDataCollectorProxy` as resolved type factory.
     * - Adds `DataCollectorTypeExtension` to type extensions.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function extend(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface
    {
        $formFactoryBuilder->setResolvedTypeFactory($this->createResolvedTypeFactoryDataCollectorProxy());
        $formFactoryBuilder->addTypeExtension($this->createDataCollectorTypeExtension());

        return $formFactoryBuilder;
    }

    /**
     * @return \Symfony\Component\Form\ResolvedFormTypeFactoryInterface
     */
    protected function createResolvedTypeFactoryDataCollectorProxy(): ResolvedFormTypeFactoryInterface
    {
        return new ResolvedTypeFactoryDataCollectorProxy(
            $this->createResolvedFormTypeFactory(),
            $this->createFormDataCollector()
        );
    }

    /**
     * @return \Symfony\Component\Form\ResolvedFormTypeFactoryInterface
     */
    protected function createResolvedFormTypeFactory(): ResolvedFormTypeFactoryInterface
    {
        return new ResolvedFormTypeFactory();
    }

    /**
     * @return \Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface
     */
    protected function createFormDataCollector(): FormDataCollectorInterface
    {
        return new FormDataCollector($this->createFormDataExtractor());
    }

    /**
     * @return \Symfony\Component\Form\Extension\DataCollector\FormDataExtractorInterface
     */
    protected function createFormDataExtractor(): FormDataExtractorInterface
    {
        return new FormDataExtractor();
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    protected function createDataCollectorTypeExtension(): FormTypeExtensionInterface
    {
        return new DataCollectorTypeExtension($this->createFormDataCollector());
    }
}
