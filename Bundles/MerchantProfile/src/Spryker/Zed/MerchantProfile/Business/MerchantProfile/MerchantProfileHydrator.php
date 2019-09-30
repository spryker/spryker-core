<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface;

class MerchantProfileHydrator implements MerchantProfileHydratorInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface
     */
    protected $merchantProfileReader;

    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface $merchantProfileReader
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileReaderInterface $merchantProfileReader,
        MerchantProfileToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantProfileReader = $merchantProfileReader;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function hydrate(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileCriteriaFilterTransfer = $this->createMerchantProfileCriteriaFilterTransfer($merchantTransfer);
        $merchantProfileTransfer = $this->merchantProfileReader->findOne($merchantProfileCriteriaFilterTransfer);

        if ($merchantProfileTransfer !== null) {
            $merchantTransfer->setMerchantProfile($merchantProfileTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer
     */
    protected function createMerchantProfileCriteriaFilterTransfer(MerchantTransfer $merchantTransfer): MerchantProfileCriteriaFilterTransfer
    {
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setIdMerchant($merchantTransfer->getIdMerchant());

        return $merchantProfileCriteriaFilterTransfer;
    }
}
