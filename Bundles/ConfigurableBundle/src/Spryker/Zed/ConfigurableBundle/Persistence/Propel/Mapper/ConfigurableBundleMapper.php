<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;

class ConfigurableBundleMapper
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate
     */
    public function mapConfigurableBundleTemplateTransferToEntity(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
    ): SpyConfigurableBundleTemplate {
        $configurableBundleTemplateEntity->fromArray(
            $configurableBundleTemplateTransfer->toArray()
        );

        return $configurableBundleTemplateEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntiy
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function mapConfigurableBundleTemplateEntityToTransfer(
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntiy,
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        return $configurableBundleTemplateTransfer->fromArray(
            $configurableBundleTemplateEntiy->toArray(),
            true
        );
    }
}
