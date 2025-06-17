<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspServiceMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_SSP_SERVICE_PAGE = 'ssp-service';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addActivePageParameter($activePage);
        $this->addIsVisibleParameter();
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isSspServiceListPageActive($activePage));
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $this->getFactory()->getCustomerClient()->isLoggedIn());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServiceMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/service-menu-item/service-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isSspServiceListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_SSP_SERVICE_PAGE;
    }
}
