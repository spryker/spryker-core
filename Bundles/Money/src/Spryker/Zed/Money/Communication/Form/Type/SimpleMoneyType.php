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
     * {@inheritDoc}
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
