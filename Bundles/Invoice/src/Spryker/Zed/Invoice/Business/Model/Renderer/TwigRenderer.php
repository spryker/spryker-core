<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Invoice\Business\Model\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Invoice\Dependency\Renderer\InvoiceToRendererInterface;

class TwigRenderer implements RendererInterface
{

    const LAYOUT_TEMPLATE = 'Invoice/Invoice/layout.html.twig';

    /**
     * @var \Spryker\Zed\Invoice\Dependency\Renderer\InvoiceToRendererInterface
     */
    protected $renderer;

    /**
     * @param \Spryker\Zed\Invoice\Dependency\Renderer\InvoiceToRendererInterface $renderer
     */
    public function __construct(InvoiceToRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return void
     */
    protected function setupTranslation()
    {
        $localeTransfer = $this->getLocaleTransfer();
        $this->renderer->setLocaleTransfer($localeTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(Store::getInstance()->getCurrentLocale()); //@todo move to dep provider

        return $localeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function render(OrderTransfer $orderTransfer)
    {
        $this->setupTranslation();

        return $this->renderer->render(
            static::LAYOUT_TEMPLATE,
            [
                'order' => $orderTransfer
            ]
        );
    }

}
