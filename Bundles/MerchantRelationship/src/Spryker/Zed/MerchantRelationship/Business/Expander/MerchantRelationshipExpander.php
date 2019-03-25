<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipExpander implements MerchantRelationshipExpanderInterface
{
    protected const FORMAT_NAME = '%s - %s';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expandWithName(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->setName($this->createMerchantRelationshipName($merchantRelationshipTransfer));

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return string
     */
    protected function createMerchantRelationshipName(MerchantRelationshipTransfer $merchantRelationshipTransfer): string
    {
        return sprintf(
            static::FORMAT_NAME,
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getName()
        );
    }
}
