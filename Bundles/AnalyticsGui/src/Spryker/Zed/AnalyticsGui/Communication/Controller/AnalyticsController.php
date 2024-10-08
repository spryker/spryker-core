<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AnalyticsGui\Communication\Controller;

use Generated\Shared\Transfer\AnalyticsCollectionTransfer;
use Generated\Shared\Transfer\AnalyticsRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\AnalyticsGui\Communication\AnalyticsGuiCommunicationFactory getFactory()
 */
class AnalyticsController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'analyticsCollection' => $this->getAnalyticsCollection(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\AnalyticsCollectionTransfer
     */
    protected function getAnalyticsCollection(): AnalyticsCollectionTransfer
    {
        $userTransfer = $this->getFactory()->getUserFacade()->getCurrentUser();
        $analyticsRequestTransfer = (new AnalyticsRequestTransfer())->setUser($userTransfer);

        return $this->executeAnalyticsCollectionExpanderPlugins(
            $analyticsRequestTransfer,
            new AnalyticsCollectionTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AnalyticsRequestTransfer $analyticsRequestTransfer
     * @param \Generated\Shared\Transfer\AnalyticsCollectionTransfer $analyticsCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AnalyticsCollectionTransfer
     */
    protected function executeAnalyticsCollectionExpanderPlugins(
        AnalyticsRequestTransfer $analyticsRequestTransfer,
        AnalyticsCollectionTransfer $analyticsCollectionTransfer
    ): AnalyticsCollectionTransfer {
        foreach ($this->getFactory()->getAnalyticsCollectionExpanderPlugins() as $analyticsCollectionExpanderPlugin) {
            $analyticsCollectionTransfer = $analyticsCollectionExpanderPlugin->expand(
                $analyticsRequestTransfer,
                $analyticsCollectionTransfer,
            );
        }

        return $analyticsCollectionTransfer;
    }
}
