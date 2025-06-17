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
class SspListMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ACTIVE_PAGE = 'activePage';

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
        $this->addParameter(static::PARAMETER_ACTIVE_PAGE, $activePage);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspListMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/customer-menu-items/customer-menu-items.twig';
    }
}
