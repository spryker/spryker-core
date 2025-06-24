<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ServicePointSearch;

use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ServicePointSearchExtension\Dependency\Plugin\ServicePointSearchDataExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class CoordinatesServicePointSearchDataExpanderPlugin extends AbstractPlugin implements ServicePointSearchDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds latitude and longitude coordinates to the service point search data.
     *
     * @api
     *
     * @param array<string, mixed> $searchData
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $searchData, ServicePointTransfer $servicePointTransfer): array
    {
        return $this->getBusinessFactory()
            ->createServicePointSearchCoordinatesExpander()
            ->expandWithCoordinates($searchData, $servicePointTransfer);
    }
}
