<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Address;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantAddressKeyGeneratorInterface;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantAddressWriter implements MerchantAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantAddressKeyGeneratorInterface
     */
    protected $merchantAddressKeyGenerator;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantAddressKeyGeneratorInterface $merchantAddressKeyGenerator
     */
    public function __construct(
        MerchantEntityManagerInterface $entityManager,
        MerchantAddressKeyGeneratorInterface $merchantAddressKeyGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->merchantAddressKeyGenerator = $merchantAddressKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function create(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $merchantAddressTransfer
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireAddress2()
            ->requireFkMerchant()
            ->requireFkCountry();

        if (empty($merchantAddressTransfer->getKey())) {
            $merchantAddressTransfer->setKey(
                $this->merchantAddressKeyGenerator->generateMerchantAddressKey()
            );
        }

        return $this->entityManager->saveMerchantAddress($merchantAddressTransfer);
    }
}
