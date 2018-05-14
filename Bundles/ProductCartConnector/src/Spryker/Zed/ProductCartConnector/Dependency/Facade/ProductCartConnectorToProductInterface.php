<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductCartConnectorToProductInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param string $concreteSku
     *
     * @return bool
     */
    public function hasProductConcrete($concreteSku);

    /**
     * @param string $abstractSku
     *
     * @return bool
     */
    public function hasProductAbstract($abstractSku);

    /**
     * @param string $concreteSku
     *
     * @return bool
     */
    public function isProductConcreteActive(string $concreteSku): bool;
}
