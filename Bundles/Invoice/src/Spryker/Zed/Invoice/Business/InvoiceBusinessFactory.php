<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Invoice\Business;

use Spryker\Zed\Invoice\Business\Model\InvoiceBuilder;
use Spryker\Zed\Invoice\Business\Model\Renderer\TwigRenderer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Invoice\InvoiceDependencyProvider;

/**
 * @method \Spryker\Zed\Invoice\InvoiceConfig getConfig()
 */
class InvoiceBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Invoice\Business\Model\InvoiceBuilderInterface
     */
    public function createInvoiceBuilder()
    {
        return new InvoiceBuilder($this->createTwigRenderer());
    }

    /**
     * @return \Spryker\Zed\Invoice\Business\Model\Renderer\RendererInterface
     */
    protected function createTwigRenderer()
    {
        return new TwigRenderer($this->getRenderer());
    }

    /**
     * @return \Spryker\Zed\Invoice\Dependency\Renderer\InvoiceToRendererInterface
     */
    protected function getRenderer()
    {
        return $this->getProvidedDependency(InvoiceDependencyProvider::RENDERER);
    }
}
