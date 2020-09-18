<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesProductConfigurationGui\Communication\SalesProductConfigurationGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function orderItemProductConfigurationAction(Request $request): array
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer */
        $itemTransfer = $request->attributes->get('orderItem');

        $salesProductConfigurationRenderPlugin = $this->getFactory()
            ->createProductConfigurationRenderStrategyPluginResolver()
            ->resolveProductConfigurationRenderPlugin($itemTransfer);

        if (!$salesProductConfigurationRenderPlugin) {
            return $this->viewResponse([
                'orderItem' => $itemTransfer,
            ]);
        }

        return $this->viewResponse([
            'template' => $salesProductConfigurationRenderPlugin->getTemplate($itemTransfer),
            'orderItem' => $itemTransfer,
        ]);
    }
}
