<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\ProductManagement\Communication\Form\Type\SingleStoreRelationType;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class SingleStoreRelationFormTypePlugin extends AbstractPlugin implements FormTypeInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return SingleStoreRelationType::class;
    }
}
