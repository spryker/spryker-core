<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\MoneyType as SymfonyMoneyType;

class SimpleMoneyType extends SymfonyMoneyType
{

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }

}
