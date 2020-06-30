<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesInvoice\Business\EmailSender\OrderInvoiceEmailSender;
use Spryker\Zed\SalesInvoice\Business\EmailSender\OrderInvoiceEmailSenderInterface;
use Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReader;
use Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReaderInterface;
use Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGenerator;
use Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGeneratorInterface;
use Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRenderer;
use Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface;
use Spryker\Zed\SalesInvoice\Business\Writer\OrderInvoiceWriter;
use Spryker\Zed\SalesInvoice\Business\Writer\OrderInvoiceWriterInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceDependencyProvider;
use Twig\Environment;

/**
 * @method \Spryker\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
 */
class SalesInvoiceBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesInvoice\Business\ReferenceGenerator\OrderInvoiceReferenceGeneratorInterface
     */
    public function createOrderInvoiceReferenceGenerator(): OrderInvoiceReferenceGeneratorInterface
    {
        return new OrderInvoiceReferenceGenerator(
            $this->getConfig(),
            $this->getSequenceNumberFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Business\Writer\OrderInvoiceWriterInterface
     */
    public function createOrderInvoiceWriter(): OrderInvoiceWriterInterface
    {
        return new OrderInvoiceWriter(
            $this->getConfig(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createOrderInvoiceReferenceGenerator(),
            $this->getOrderInvoiceBeforeSavePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReaderInterface
     */
    public function createOrderInvoiceReader(): OrderInvoiceReaderInterface
    {
        return new OrderInvoiceReader(
            $this->getRepository(),
            $this->createOrderInvoiceRenderer(),
            $this->getSalesFacade(),
            $this->getOrderInvoicesExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface
     */
    public function createOrderInvoiceRenderer(): OrderInvoiceRendererInterface
    {
        return new OrderInvoiceRenderer(
            $this->getTwigEnvironment()
        );
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Business\EmailSender\OrderInvoiceEmailSenderInterface
     */
    public function createOrderInvoiceEmailSender(): OrderInvoiceEmailSenderInterface
    {
        return new OrderInvoiceEmailSender(
            $this->getEntityManager(),
            $this->createOrderInvoiceReader(),
            $this->getSalesFacade(),
            $this->getMailFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SalesInvoiceToSequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface
     */
    public function getMailFacade(): SalesInvoiceToMailFacadeInterface
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesInvoiceToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoiceBeforeSavePluginInterface[]
     */
    public function getOrderInvoiceBeforeSavePlugins(): array
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::PLUGINS_ORDER_INVOICE_BEFORE_SAVE);
    }

    /**
     * @return \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoicesExpanderPluginInterface[]
     */
    public function getOrderInvoicesExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesInvoiceDependencyProvider::PLUGINS_ORDER_INVOICES_EXPANDER);
    }
}
