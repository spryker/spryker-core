<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Invoice\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Invoice\Business\Model\Renderer\RendererInterface;

class InvoiceBuilder implements InvoiceBuilderInterface
{
    /**
     * @var \Spryker\Zed\Invoice\Business\Model\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @param \Spryker\Zed\Invoice\Business\Model\Renderer\RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function buildInvoice(OrderTransfer $orderTransfer)
    {
        $st =  $this->renderer->render($orderTransfer);

        echo $st;
        exit;

        $br = 1;
    }
}
