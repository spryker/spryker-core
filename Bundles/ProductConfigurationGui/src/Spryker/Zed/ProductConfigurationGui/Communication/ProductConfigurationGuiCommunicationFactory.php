<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductConfigurationGui\Communication\Expander\ProductConfigurationTableDataExpander;
use Spryker\Zed\ProductConfigurationGui\Communication\Expander\ProductConfigurationTableDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiRepositoryInterface getRepository()
 */
class ProductConfigurationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationGui\Communication\Expander\ProductConfigurationTableDataExpanderInterface
     */
    public function createProductConfigurationTableDataExpander(): ProductConfigurationTableDataExpanderInterface
    {
        return new ProductConfigurationTableDataExpander($this->getRepository());
    }
}
