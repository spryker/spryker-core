<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment;

use Spryker\Yves\DummyMarketplacePayment\Expander\DummyMarketplacePaymentExpander;
use Spryker\Yves\DummyMarketplacePayment\Expander\DummyMarketplacePaymentExpanderInterface;
use Spryker\Yves\DummyMarketplacePayment\Form\Constraint\DateOfBirthValueConstraint;
use Spryker\Yves\DummyMarketplacePayment\Form\DataProvider\InvoiceFormDataProvider;
use Spryker\Yves\DummyMarketplacePayment\Form\InvoiceSubForm;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Yves\DummyMarketplacePayment\DummyMarketplacePaymentConfig getConfig()
 */
class DummyMarketplacePaymentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\DummyMarketplacePayment\Expander\DummyMarketplacePaymentExpanderInterface
     */
    public function createMarketplacePaymentExpander(): DummyMarketplacePaymentExpanderInterface
    {
        return new DummyMarketplacePaymentExpander();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createInvoiceSubForm(): SubFormInterface
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInvoiceFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new InvoiceFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createDateOfBirthValueConstraint(): Constraint
    {
        return new DateOfBirthValueConstraint();
    }
}
