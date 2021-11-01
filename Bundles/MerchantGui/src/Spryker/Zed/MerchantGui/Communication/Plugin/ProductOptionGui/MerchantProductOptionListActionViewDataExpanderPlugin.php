<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Plugin\ProductOptionGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListActionViewDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantProductOptionListActionViewDataExpanderPlugin extends AbstractPlugin implements ProductOptionListActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands data with merchant collection.
     *
     * @api
     *
     * @param array<mixed> $viewData
     *
     * @return array<mixed>
     */
    public function expand(array $viewData): array
    {
        return $this->getFactory()
            ->createMerchantListDataExpander()
            ->expandData($viewData);
    }
}
