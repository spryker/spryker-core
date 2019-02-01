<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantAddressMapperInterface
     */
    protected $merchantAddressMapper;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantAddressMapperInterface $merchantAddressMapper
     */
    public function __construct(MerchantAddressMapperInterface $merchantAddressMapper)
    {
        $this->merchantAddressMapper = $merchantAddressMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $spyMerchant
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    public function mapMerchantTransferToEntity(
        MerchantTransfer $merchantTransfer,
        SpyMerchant $spyMerchant
    ): SpyMerchant {
        $spyMerchant->fromArray(
            $merchantTransfer->modifiedToArray(false)
        );

        return $spyMerchant;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $spyMerchant
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapEntityToMerchantTransfer(
        SpyMerchant $spyMerchant,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        $merchantTransfer = $merchantTransfer->fromArray(
            $spyMerchant->toArray(),
            true
        );

        foreach ($spyMerchant->getSpyMerchantAddresses() as $spyMerchantAddress) {
            $merchantTransfer->setAddress(
                $this->merchantAddressMapper->mapSpyMerchantAddressEntityToMerchantAddressTransfer(
                    $spyMerchantAddress,
                    new MerchantAddressTransfer()
                )
            );

            break;
        }

        return $merchantTransfer;
    }
}
