<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin;

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
    public function createSubForm();

    /**
     * Specification:
     * - Provides a property path for an injected form
     *
     * @api
     *
     * @return string
     */
    public function getPropertyPath();

    /**
     * Specification:
     * - Provides a name for an injected form
     *
     * @api
     *
     * @return string
     */
    public function getName();

    /**
     * Specification:
     * - Defines a payment provider name
     *
     * @api
     *
     * @return string
     */
    public function getPaymentProvider();

    /**
     * Specification:
     * - Defines a payment method name
     *
     * @api
     *
     * @return string
     */
    public function getPaymentMethod();

    /**
     * Specification:
     * - Provides data to fill an injected form
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($dataTransfer);

    /**
     * Specification:
     * - Provides options/configuration for building an injected form
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getOptions($dataTransfer);
}
