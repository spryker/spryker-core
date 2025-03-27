<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Widget;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspInquiryListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     * @param string|null $moreLink
     */
    public function __construct(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer, ?string $moreLink = null)
    {
        $this->addParameter('totalItems', $sspInquiryCollectionTransfer->getPaginationOrFail()->getNbResults());
        $this->addParameter('inquiries', $sspInquiryCollectionTransfer->getSspInquiries());
        $this->addParameter('moreLink', $moreLink);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspInquiryListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspInquiryManagement/views/dashboard-inquiry/dashboard-inquiry.twig';
    }
}
