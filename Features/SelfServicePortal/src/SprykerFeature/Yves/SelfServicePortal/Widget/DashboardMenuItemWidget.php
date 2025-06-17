<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DashboardMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_DASHBOARD_PAGE = 'dashboard';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addActivePageParameter($activePage);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DashboardMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/dashboard-menu-item/dashboard-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(
            static::PARAMETER_IS_ACTIVE_PAGE,
            $activePage === static::PAGE_KEY_DASHBOARD_PAGE,
        );
    }
}
