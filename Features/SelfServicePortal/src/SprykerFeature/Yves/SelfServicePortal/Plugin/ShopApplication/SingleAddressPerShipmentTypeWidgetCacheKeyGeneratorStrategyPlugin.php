<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\SelfServicePortal\Widget\SingleAddressPerShipmentTypeWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

class SingleAddressPerShipmentTypeWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * @param array<string, mixed> $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getWidgetClassName(): string
    {
        return SingleAddressPerShipmentTypeWidget::class;
    }
}
