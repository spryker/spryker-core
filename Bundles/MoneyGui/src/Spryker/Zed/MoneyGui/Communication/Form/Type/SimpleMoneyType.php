<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\Type;

use Spryker\Zed\Gui\Communication\Form\Type\FormattedMoneyType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;

/**
 * @method \Spryker\Zed\MoneyGui\Communication\MoneyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MoneyGui\MoneyGuiConfig getConfig()
 */
class SimpleMoneyType extends AbstractType
{
    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return FormattedMoneyType::class;
    }
}
