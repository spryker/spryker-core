<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\MoneyGui\Communication\Form\Type\MoneyCollectionType;

/**
 * @method \Spryker\Zed\MoneyGui\Communication\MoneyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MoneyGui\MoneyGuiConfig getConfig()
 */
class MoneyCollectionFormTypePlugin extends AbstractPlugin implements FormTypeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return MoneyCollectionType::class;
    }
}
