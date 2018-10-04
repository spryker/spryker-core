<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Store\Communication\Form\Type\StoreRelationToggleType;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 */
class StoreRelationToggleFormTypePlugin extends AbstractPlugin implements FormTypeInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getType()
    {
        return StoreRelationToggleType::class;
    }
}
