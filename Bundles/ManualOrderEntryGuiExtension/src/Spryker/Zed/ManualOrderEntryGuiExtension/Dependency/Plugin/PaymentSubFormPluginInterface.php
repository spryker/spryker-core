<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;

interface PaymentSubFormPluginInterface
{
    /**
     * Specification:
     * - Provides a form type for an injection
     *
     * @api
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\AbstractType
     */
    public function createSubForm(): AbstractType;

    /**
     * Specification:
     * - Provides a property path for an injected form
     *
     * @api
     *
     * @return string
     */
    public function getPropertyPath(): string;

    /**
     * Specification:
     * - Provides a name for an injected form
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Defines a payment provider name
     *
     * @api
     *
     * @return string
     */
    public function getPaymentProvider(): string;

    /**
     * Specification:
     * - Defines a payment method name
     *
     * @api
     *
     * @return string
     */
    public function getPaymentMethod(): string;

    /**
     * Specification:
     * - Provides data to fill an injected form
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Provides options/configuration for building an injected form
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(QuoteTransfer $quoteTransfer): array;
}
