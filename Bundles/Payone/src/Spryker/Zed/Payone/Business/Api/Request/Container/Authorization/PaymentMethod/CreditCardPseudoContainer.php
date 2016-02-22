<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class CreditCardPseudoContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $pseudocardpan;

    /**
     * @param string $pseudoCardPan
     *
     * @return void
     */
    public function setPseudoCardPan($pseudoCardPan)
    {
        $this->pseudocardpan = $pseudoCardPan;
    }

    /**
     * @return string
     */
    public function getPseudoCardPan()
    {
        return $this->pseudocardpan;
    }

}
