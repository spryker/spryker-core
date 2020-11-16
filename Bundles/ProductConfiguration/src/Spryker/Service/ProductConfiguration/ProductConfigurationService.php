<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ProductConfiguration\ProductConfigurationServiceFactory getFactory()
 */
class ProductConfigurationService extends AbstractService implements ProductConfigurationServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string
    {
        return $this->getFactory()
            ->createProductConfigurationInstanceHashGenerator()
            ->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);
    }
}
