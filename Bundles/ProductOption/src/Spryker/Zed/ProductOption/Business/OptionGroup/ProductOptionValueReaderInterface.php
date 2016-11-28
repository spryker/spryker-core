<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOption\Business\OptionGroup;

interface ProductOptionValueReaderInterface
{

    /**
     * @param int $idProductOptionValue
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValue);

}
