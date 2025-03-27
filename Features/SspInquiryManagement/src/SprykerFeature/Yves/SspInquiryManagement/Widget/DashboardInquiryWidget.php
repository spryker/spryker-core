<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Widget;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DashboardInquiryWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer|null $sspInquiryCollectionTransfer
     */
    public function __construct(?SspInquiryCollectionTransfer $sspInquiryCollectionTransfer)
    {
        $this->addParameter('totalItems', $sspInquiryCollectionTransfer?->getPagination()?->getNbResults());
        $this->addParameter('inquiries', $sspInquiryCollectionTransfer?->getSspInquiries());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DashboardInquiryWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspInquiryManagement/views/dashboard-inquiry/dashboard-inquiry.twig';
    }
}
