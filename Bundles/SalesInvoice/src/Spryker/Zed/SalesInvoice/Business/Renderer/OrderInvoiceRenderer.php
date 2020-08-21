<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Renderer;

use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Twig\Environment;

class OrderInvoiceRenderer implements OrderInvoiceRendererInterface
{
    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function renderOrderInvoice(OrderInvoiceTransfer $orderInvoiceTransfer, OrderTransfer $orderTransfer): string
    {
        $this->setLocaleTransfer($orderTransfer->getLocale());

        return $this->twigEnvironment->render($orderInvoiceTransfer->getTemplatePath(), [
            'invoice' => $orderInvoiceTransfer,
            'order' => $orderTransfer,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function setLocaleTransfer(LocaleTransfer $localeTransfer): void
    {
        /** @var \Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin $translator */
        $translator = $this->twigEnvironment->getExtension(TwigTranslatorPlugin::class);

        $translator->setLocaleTransfer($localeTransfer);
    }
}
