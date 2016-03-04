<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Builder;

interface ProductBuilderInterface
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProduct(array $data);

}
