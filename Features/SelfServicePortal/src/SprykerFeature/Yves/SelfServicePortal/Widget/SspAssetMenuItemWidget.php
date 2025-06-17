<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspAssetMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_ASSET_PAGE = 'asset';

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
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isAssetListPageActive($activePage));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspAssetMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-menu-item/asset-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isAssetListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_ASSET_PAGE;
    }
}
