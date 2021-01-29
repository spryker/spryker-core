<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeBridge;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeBridge;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeBridge;
use Twig\Environment;

/**
 * @method \Spryker\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 */
class SalesInvoiceDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'FACADE_MAIL';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    public const FACADE_SALES = 'FACADE_SALES';
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';
    public const PLUGINS_ORDER_INVOICE_BEFORE_SAVE = 'PLUGINS_ORDER_INVOICE_BEFORE_SAVE';
    public const PLUGINS_ORDER_INVOICES_EXPANDER = 'PLUGINS_ORDER_INVOICES_EXPANDER';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addTwigEnvironment($container);
        $container = $this->addMailFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addOrderInvoiceBeforeSavePlugins($container);
        $container = $this->addOrderInvoicesExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container): Container
    {
        $container->set(static::FACADE_SEQUENCE_NUMBER, function (Container $container) {
            return new SalesInvoiceToSequenceNumberFacadeBridge(
                $container->getLocator()->sequenceNumber()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigEnvironment(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (ContainerInterface $container) {
            $twig = $container->getApplicationService(static::SERVICE_TWIG);
            if (!$twig->hasExtension(TwigTranslatorPlugin::class)) {
                $translator = new TwigTranslatorPlugin();
                $twig->addExtension($translator);
            }

            return $twig;
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment(): Environment
    {
        $pimplePlugin = new Pimple();

        return $pimplePlugin->getApplication()['twig'];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new SalesInvoiceToMailFacadeBridge(
                $container->getLocator()->mail()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesInvoiceToSalesFacadeBridge(
                $container->getLocator()->sales()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderInvoiceBeforeSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_INVOICE_BEFORE_SAVE, function () {
            return $this->getOrderInvoiceBeforeSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderInvoicesExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_INVOICES_EXPANDER, function () {
            return $this->getOrderInvoiceBeforeSavePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoiceBeforeSavePluginInterface[]
     */
    protected function getOrderInvoiceBeforeSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoicesExpanderPluginInterface[]
     */
    protected function getOrderInvoicesExpanderPlugins(): array
    {
        return [];
    }
}
