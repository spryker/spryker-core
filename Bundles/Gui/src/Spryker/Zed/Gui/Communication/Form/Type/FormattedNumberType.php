<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class FormattedNumberType extends AbstractLocaleFormattedType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'formatted_number';

    /**
     * @return string
     */
    public function getParent(): string
    {
        return NumberType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }
}
