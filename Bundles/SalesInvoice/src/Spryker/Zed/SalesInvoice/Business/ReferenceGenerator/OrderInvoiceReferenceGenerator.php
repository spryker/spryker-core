<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\ReferenceGenerator;

use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceConfig;

class OrderInvoiceReferenceGenerator implements OrderInvoiceReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\SalesInvoice\SalesInvoiceConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface|\Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeInterface
     */
    protected $sequenceNumberFacade;

    /**
     * @param \Spryker\Zed\SalesInvoice\SalesInvoiceConfig $config
     * @param \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSequenceNumberFacadeInterface $sequenceNumberFacade
     */
    public function __construct(
        SalesInvoiceConfig $config,
        SalesInvoiceToSequenceNumberFacadeInterface $sequenceNumberFacade
    ) {
        $this->config = $config;
        $this->sequenceNumberFacade = $sequenceNumberFacade;
    }

    /**
     * @return string
     */
    public function generateOrderInvoiceReference(): string
    {
        return $this->sequenceNumberFacade->generate($this->config->getOrderInvoiceReferenceSequence());
    }
}
