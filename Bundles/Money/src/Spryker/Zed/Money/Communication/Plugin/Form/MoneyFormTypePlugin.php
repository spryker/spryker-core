<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Money\Communication\Form\Type\MoneyType;

/**
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 */
class MoneyFormTypePlugin extends AbstractPlugin implements FormTypeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType()
    {
        return MoneyType::class;
    }
}
