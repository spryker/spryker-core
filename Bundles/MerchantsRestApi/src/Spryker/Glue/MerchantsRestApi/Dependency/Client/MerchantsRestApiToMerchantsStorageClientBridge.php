<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use ArrayObject;
use Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantStorageProfileTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\UrlTransfer;

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
        $merchantStorageProfileAddressTransfer3 = (new MerchantStorageProfileAddressTransfer())
            ->fromArray([
                'country_name' => 'Germany',
                'address1' => 'Caroline-Michaelis-Straße',
                'address2' => '8',
                'address3' => '',
                'city' => 'Berlin',
                'zip_code' => '10115',
                'email' => NULL,
            ], true);

        $merchantProfileData = [
            'merchant_name' => 'Kudu Merchant',
            'contact_person_role' => 'E-Commerce Manager',
            'contact_person_title' => 'Mr',
            'contact_person_first_name' => 'Harald',
            'contact_person_last_name' => 'Schmidt',
            'contact_person_phone' => '030/123456789',
            'logo_url' => 'http://d2s0ynfc62ej12.cloudfront.net/image/07de3f84-842b-4015-927c-f4dca3a83ab7.png',
            'public_email' => 'support@kudu.org',
            'public_phone' => '030 234567891',
            'description_glossary_key' => 'merchant.description_glossary_key.1',
            'banner_url_glossary_key' => 'merchant.banner_url_glossary_key.1',
            'delivery_time_glossary_key' => 'merchant.delivery_time_glossary_key.1',
            'terms_conditions_glossary_key' => 'merchant.terms_conditions_glossary_key.1',
            'cancellation_policy_glossary_key' => 'merchant.cancellation_policy_glossary_key.1',
            'imprint_glossary_key' => 'merchant.imprint_glossary_key.1',
            'data_privacy_glossary_key' => 'merchant.data_privacy_glossary_key.1',
            'id_merchant_profile' => 3,
            'fk_merchant' => 1,
            'latitude' => NULL,
            'longitude' => NULL,
            'fax_number' => '030 234567800',
        ];
        $merchantStorageProfile = (new MerchantStorageProfileTransfer())
            ->setPublicEmail('email-test')
            ->setTermsConditionsGlossaryKey('TermsConditionsGlossaryKey')
            ->addAddress($merchantStorageProfileAddressTransfer1)
            ->addAddress($merchantStorageProfileAddressTransfer2)
            ->addAddress($merchantStorageProfileAddressTransfer3)
            ->fromArray($merchantProfileData, true);

        $urlTransfers = [
            (new UrlTransfer())->setUrl('/en/merchant/kudu-merchant-1')
                ->setFkLocale(66)
                ->setIdUrl(508)
                ->setLocaleName('en_US')
                ->setFkResourceMerchantProfile(1),
        ];

        return [
            (new MerchantStorageTransfer())
                ->setIdMerchant(1)
                ->setMerchantReference('MER000006')
                ->setName('Kudu Merchant')
                ->setMerchantUrl('/en/merchant/kudu-merchant-1')
                ->setUrlCollection(new ArrayObject($urlTransfers))
                ->setMerchantStorageProfile($merchantStorageProfile),
        ];
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOneByMerchantReference(string $merchantReference): ?MerchantStorageTransfer
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

        $merchantStorageProfileAddressTransfer3 = (new MerchantStorageProfileAddressTransfer())
            ->fromArray([
                'country_name' => 'Germany',
                'address1' => 'Caroline-Michaelis-Straße',
                'address2' => '8',
                'address3' => '',
                'city' => 'Berlin',
                'zip_code' => '10115',
                'email' => NULL,
            ], true);

        $merchantProfileData = [
            'contact_person_role' => 'E-Commerce Manager',
            'contact_person_title' => 'Mr',
            'contact_person_first_name' => 'Harald',
            'contact_person_last_name' => 'Schmidt',
            'contact_person_phone' => '030/123456789',
            'logo_url' => 'http://d2s0ynfc62ej12.cloudfront.net/image/07de3f84-842b-4015-927c-f4dca3a83ab7.png',
            'public_email' => 'support@kudu.org',
            'public_phone' => '030 234567891',
            'description_glossary_key' => 'merchant.description_glossary_key.1',
            'banner_url_glossary_key' => 'merchant.banner_url_glossary_key.1',
            'delivery_time_glossary_key' => 'merchant.delivery_time_glossary_key.1',
            'terms_conditions_glossary_key' => 'merchant.terms_conditions_glossary_key.1',
            'cancellation_policy_glossary_key' => 'merchant.cancellation_policy_glossary_key.1',
            'imprint_glossary_key' => 'merchant.imprint_glossary_key.1',
            'data_privacy_glossary_key' => 'merchant.data_privacy_glossary_key.1',
            'id_merchant_profile' => 3,
            'fk_merchant' => 1,
            'latitude' => NULL,
            'longitude' => NULL,
            'fax_number' => '030 234567800',
        ];
        $merchantStorageProfile = (new MerchantStorageProfileTransfer())
            ->addAddress($merchantStorageProfileAddressTransfer1)
            ->addAddress($merchantStorageProfileAddressTransfer2)
            ->addAddress($merchantStorageProfileAddressTransfer3)
            ->fromArray($merchantProfileData, true);

        return (new MerchantStorageTransfer())
            ->setIdMerchant(1)
            ->setMerchantReference('MER000006')
            ->setName('Kudu Merchant')
            ->setMerchantUrl('/en/merchant/kudu-merchant-1')
            ->setMerchantStorageProfile($merchantStorageProfile);
    }
}
