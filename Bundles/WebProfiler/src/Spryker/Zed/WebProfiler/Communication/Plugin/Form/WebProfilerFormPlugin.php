<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler\Communication\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Zed\WebProfiler\Communication\WebProfilerCommunicationFactory getFactory()
 * @method \Spryker\Zed\WebProfiler\WebProfilerConfig getConfig()
 */
class WebProfilerFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeFactoryDataCollectorProxy` as resolved type factory.
     * - Adds `Symfony\Component\Form\Extension\DataCollector\Type\DataCollectorTypeExtension` to type extensions.
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
        $formFactoryBuilder->setResolvedTypeFactory($this->getFactory()->createResolvedTypeFactoryDataCollectorProxy());
        $formFactoryBuilder->addTypeExtension($this->getFactory()->createDataCollectorTypeExtension());

        return $formFactoryBuilder;
    }
}
