<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Business\Expander\MerchantExpanderInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Generator\MerchantReferenceGeneratorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Mapper\MerchantMapperInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantFacadeInterface;

class MerchantCreator implements MerchantCreatorInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToMerchantFacadeInterface $merchantFacade,
        protected MerchantMapperInterface $merchantMapper,
        protected MerchantExpanderInterface $merchantExpander,
        protected MerchantReferenceGeneratorInterface $merchantReferenceGenerator
    ) {
    }

    public function createMerchant(MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer): MerchantTransfer
    {
        $merchantTransfer = $this->merchantMapper
            ->mapMerchantRegistrationRequestTransferToMerchantTransfer($merchantRegistrationRequestTransfer, new MerchantTransfer());
        $merchantTransfer = $this->merchantExpander->expandMerchantTransferWithUrls($merchantTransfer);
        $merchantTransfer->setMerchantReference($this->merchantReferenceGenerator->generateMerchantReference());

        return $this->merchantFacade->createMerchant($merchantTransfer)->getMerchantOrFail();
    }
}
