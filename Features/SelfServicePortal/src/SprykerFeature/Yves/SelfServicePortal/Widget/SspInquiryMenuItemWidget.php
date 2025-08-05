<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspInquiryMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_SSP_INQUIRY_PAGE = 'ssp-inquiry';

    public function __construct(string $activePage)
    {
        $this->addActivePageParameter($activePage);
    }

    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isSspInquiryListPageActive($activePage));
    }

    public static function getName(): string
    {
        return 'SspInquiryMenuItemWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/inquiry-menu-item/inquiry-menu-item.twig';
    }

    protected function isSspInquiryListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_SSP_INQUIRY_PAGE;
    }
}
