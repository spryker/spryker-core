<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspInquiryListWidget extends AbstractWidget
{
    public function __construct(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer, ?string $moreLink = null)
    {
        $this->addParameter('totalItems', $sspInquiryCollectionTransfer->getPaginationOrFail()->getNbResults());
        $this->addParameter('inquiries', $sspInquiryCollectionTransfer->getSspInquiries());
        $this->addParameter('moreLink', $moreLink);
    }

    public static function getName(): string
    {
        return 'SspInquiryListWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/dashboard-inquiry/dashboard-inquiry.twig';
    }
}
