<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductDiscountConnector\Dependency\Facade;

interface ProductDiscountConnectorToProductInterface
{

    /**
     * @param string $abstractSku
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessorByAbstractSku($abstractSku);

}
