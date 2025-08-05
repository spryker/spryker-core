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

    public function __construct(string $activePage)
    {
        $this->addActivePageParameter($activePage);
    }

    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isAssetListPageActive($activePage));
    }

    public static function getName(): string
    {
        return 'SspAssetMenuItemWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-menu-item/asset-menu-item.twig';
    }

    protected function isAssetListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_ASSET_PAGE;
    }
}
