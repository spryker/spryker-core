<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\SspAssetManagement;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Zed\SspAssetManagement\Dependency\Plugin\SspAssetManagementExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementBusinessFactory getBusinessFactory()
 */
class ServiceSspAssetManagementExpanderPlugin extends AbstractPlugin implements SspAssetManagementExpanderPluginInterface
{
    use PermissionAwareTrait;

    /**
     * {@inheritDoc}
     * - Expands the SspAssetCollectionTransfer with services collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expand(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        return $this->getBusinessFactory()->createSspAssetExpander()->expandSspAssetWithServices($sspAssetCollectionTransfer, $sspAssetCriteriaTransfer);
    }
}
