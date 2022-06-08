<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment\PaymentType;

class PaymentDataProvider implements FormDataProviderInterface
{
    /**
     * @var array<\Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface>
     */
    protected $subFormPlugins;

    /**
     * @param array<\Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface> $subFormPlugins
     */
    public function __construct($subFormPlugins)
    {
        $this->subFormPlugins = $subFormPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array<string, mixed>
     */
    public function getOptions($transfer): array
    {
        $options = [];
        foreach ($this->subFormPlugins as $subFormPlugin) {
            $options = array_merge(
                $options,
                $subFormPlugin->getOptions($transfer),
            );
        }

        return [
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            PaymentType::OPTIONS_FIELD_NAME => $options,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        foreach ($this->subFormPlugins as $subFormPlugin) {
            $transfer = $subFormPlugin->getData($transfer);
        }

        return $transfer;
    }
}
