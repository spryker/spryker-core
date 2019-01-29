<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantAddressForm;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantAddressFormTypePlugin extends AbstractPlugin implements FormTypeInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getType()
    {
        return MerchantAddressForm::class;
    }
}
