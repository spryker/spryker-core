<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\ResourceShareExtension;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class SharedCartDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    protected const KEY_SHARE_OPTION = 'share_option';

    protected const KEY_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';

    /**
     * {@inheritdoc}
     * - Expands ResourceShareTransfer::ResourceShareDataTransfer with idCompanyBusinessUnit value.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $idCompanyBusinessUnit = $resourceShareDataTransfer->getData()[static::KEY_ID_COMPANY_BUSINESS_UNIT] ?? null;
        $resourceShareDataTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * {@inheritdoc}
     * - Checks if resource data contains company business unit id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $shareOption = $resourceShareDataTransfer->getData()[static::KEY_SHARE_OPTION] ?? null;
        if (!$shareOption) {
            return false;
        }

        return in_array($shareOption, [SharedCartConfig::PERMISSION_GROUP_READ_ONLY, SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS], true);
    }
}
