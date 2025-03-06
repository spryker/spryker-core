<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class CreateOrderSspInquiryLinkWidget extends AbstractWidget
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
        return 'CreateOrderSspInquiryLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspInquiryManagement/views/ssp-inquiry-order-action-button/ssp-inquiry-order-action-button.twig';
    }
}
