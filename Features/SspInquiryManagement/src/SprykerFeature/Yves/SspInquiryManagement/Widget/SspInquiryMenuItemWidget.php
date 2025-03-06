<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Widget;

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

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addActivePageParameter($activePage);
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isSspInquiryListPageActive($activePage));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspInquiryMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspInquiryManagement/views/ssp-inquiry-menu-item/ssp-inquiry-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isSspInquiryListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_SSP_INQUIRY_PAGE;
    }
}
