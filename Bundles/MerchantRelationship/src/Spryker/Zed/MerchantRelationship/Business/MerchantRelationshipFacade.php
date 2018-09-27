<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 */
class MerchantRelationshipFacade extends AbstractFacade implements MerchantRelationshipFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipWriter()
            ->create($merchantRelationshipTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function updateMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipWriter()
            ->update($merchantRelationshipTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->getFactory()
            ->createMerchantRelationshipWriter()
            ->delete($merchantRelationshipTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipReader()
            ->getMerchantRelationshipById($merchantRelationshipTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(): array
    {
        return $this->getRepository()
            ->getMerchantRelationshipCollection();
    }
}
