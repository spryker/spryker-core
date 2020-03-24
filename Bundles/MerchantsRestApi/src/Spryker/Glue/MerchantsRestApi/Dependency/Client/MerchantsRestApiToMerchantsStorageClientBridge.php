<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantStorageProfileTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;

class MerchantsRestApiToMerchantsStorageClientBridge implements MerchantsRestApiToMerchantsStorageClientInterface
{
    /**
     * @param string[] $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function findByMerchantReference(array $merchantReferences): array
    {
        $merchantStorageProfileAddressTransfer1 = (new MerchantStorageProfileAddressTransfer())
            ->setAddress1('address1')
            ->setAddress2('address2')
            ->setCity('City')
            ->setCountryName('CountryName');

        $merchantStorageProfileAddressTransfer2 = (new MerchantStorageProfileAddressTransfer())
            ->setAddress1('address3')
            ->setAddress2('address4')
            ->setCity('City2')
            ->setCountryName('CountryName2');

        $merchantStorageProfile = (new MerchantStorageProfileTransfer())
            ->setMerchantUrl('url-test')
            ->setPublicEmail('email-test')
            ->setTermsConditionsGlossaryKey('TermsConditionsGlossaryKey')
            ->addAddressCollection($merchantStorageProfileAddressTransfer1)
            ->addAddressCollection($merchantStorageProfileAddressTransfer2);

        return [
            (new MerchantStorageTransfer())
                ->setIdMerchant(1)
                ->setMerchantReference('MER000006')
                ->setName('Kudu Merchant')
                ->setMerchantStorageProfile($merchantStorageProfile),
        ];
    }
}
