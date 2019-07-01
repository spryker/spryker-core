<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Order;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class OrderType extends AbstractType
{
    public const TYPE_NAME = 'order';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
    }
}
