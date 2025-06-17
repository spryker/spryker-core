<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class CreateOrderSspInquiryWidget extends AbstractWidget
{
    /**
     * @param string $orderReference
     * @param int $orderId
     */
    public function __construct(string $orderReference, int $orderId)
    {
        $this->addParameter('orderReference', $orderReference);
        $this->addParameter('orderId', $orderId);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CreateOrderSspInquiryWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/inquiry-order-action-button/inquiry-order-action-button.twig';
    }
}
