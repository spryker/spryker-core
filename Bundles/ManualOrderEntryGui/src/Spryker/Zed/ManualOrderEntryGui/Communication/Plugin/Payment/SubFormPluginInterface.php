<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment;

interface SubFormPluginInterface
{
    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\AbstractType
     */
    public function createSubForm();

    /**
     * @return string
     */
    public function getPropertyPath();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPaymentProvider();

    /**
     * @return string
     */
    public function getPaymentMethod();

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer);
}
