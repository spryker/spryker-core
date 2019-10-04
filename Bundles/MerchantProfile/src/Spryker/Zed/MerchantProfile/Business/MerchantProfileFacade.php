<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business;

use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface getRepository()
 */
class MerchantProfileFacade extends AbstractFacade implements MerchantProfileFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileWriter()
            ->saveMerchantProfile($merchantProfileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileReader()
            ->findOne($merchantProfileCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer|null $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function find(?MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer = null): MerchantProfileCollectionTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileReader()
            ->find($merchantProfileCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function hydrateMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileHydrator()
            ->hydrate($merchantTransfer);
    }
}
