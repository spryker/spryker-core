<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAttribute\Communication\Formatter\MultiSelectAttributeFormatter;
use Spryker\Zed\ProductAttribute\Communication\Formatter\MultiSelectAttributeFormatterInterface;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductAttribute\Communication\Formatter\MultiSelectAttributeFormatterInterface
     */
    public function createMultiSelectAttributeFormatter(): MultiSelectAttributeFormatterInterface
    {
        return new MultiSelectAttributeFormatter();
    }
}
