<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

interface GiftCardRepositoryInterface
{
    /**
     * @param string[] $abstractSkus
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationForProductAbstractTransfer[]
     */
    public function getGiftCardAbstractConfigurationsForProductAbstractByAbstractSkus(array $abstractSkus): array;

    /**
     * @param string[] $concreteSkus
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationForProductTransfer[]
     */
    public function getGiftCardConcreteConfigurationsForProductByConcreteSkus(array $concreteSkus): array;
}
