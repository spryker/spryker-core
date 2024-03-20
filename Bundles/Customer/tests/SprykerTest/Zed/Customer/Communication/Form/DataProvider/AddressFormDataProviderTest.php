<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Form
 * @group DataProvider
 * @group AddressFormDataProviderTest
 * Add your own group annotations below this line
 */
class AddressFormDataProviderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Customer\CustomerCommunicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const NAME_COUNTRY = 'US';

    /**
     * @var int
     */
    protected const ID_COUNTRY = 1;

    /**
     * @return void
     */
    public function testGetOptionReturnRequiredKeys(): void
    {
        // Arrange
        /** @var \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory $customerCommunicationFactory */
        $customerCommunicationFactory = $this->tester->getFactory();
        $dataProvider = $customerCommunicationFactory->createAddressFormDataProvider();

        // Action
        $options = $dataProvider->getOptions();

        // Assert
        $this->assertSame(
            [
                AddressForm::OPTION_SALUTATION_CHOICES,
                AddressForm::OPTION_COUNTRY_CHOICES,
            ],
            array_keys($options),
        );
    }

    /**
     * @return void
     */
    public function testGetOptionContriesReturnsCountriesForDynamicStore(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('This test requires DynamicStore to be enabled.');
        }

        // Arrange
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIdCountry(static::ID_COUNTRY);
        $countryTransfer->setName(static::NAME_COUNTRY);

        /** @var \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory $customerCommunicationFactory */
        $customerCommunicationFactory = $this->tester->getFactory();
        $countryFacadeMock = $this->tester->createCountryFacadeMock();
        $countryFacadeMock->method('getAvailableCountries')->willReturn(
            (new CountryCollectionTransfer())
            ->addCountries($countryTransfer),
        );
        $storeFacadeMock = $this->tester->createStoreFacadeMock();
        $storeFacadeMock->method('isDynamicStoreEnabled')->willReturn(true);
        $dataProvider = new AddressFormDataProvider(
            $countryFacadeMock,
            $this->tester->createCustomerQueryContainerMock(),
            $storeFacadeMock,
        );

        // Action
        $options = $dataProvider->getOptions();

        // Assert
        $this->assertEquals([$countryTransfer->getIdCountry() => $countryTransfer->getName()], $options[AddressForm::OPTION_COUNTRY_CHOICES]);
    }
}
