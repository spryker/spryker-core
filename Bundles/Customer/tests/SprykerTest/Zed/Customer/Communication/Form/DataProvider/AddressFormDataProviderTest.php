<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use Spryker\Zed\Customer\Communication\Form\AddressForm;

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
}
