<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\PaymentGui\Communication\Form\DataProvider\PaymentMethodFormDataProvider;
use Spryker\Zed\PaymentGui\Communication\Form\DataProvider\ViewPaymentMethodFormDataProvider;
use Spryker\Zed\PaymentGui\Communication\Form\PaymentMethod\PaymentMethodForm;
use Spryker\Zed\PaymentGui\Communication\Form\PaymentMethod\ViewPaymentMethodForm;
use Spryker\Zed\PaymentGui\Communication\Table\PaymentMethodTable;
use Spryker\Zed\PaymentGui\Communication\Tabs\PaymentMethodTabs;
use Spryker\Zed\PaymentGui\Dependency\Facade\PaymentGuiToPaymentFacadeInterface;
use Spryker\Zed\PaymentGui\PaymentGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class PaymentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PaymentGui\Communication\Table\PaymentMethodTable
     */
    public function createPaymentMethodTable(): PaymentMethodTable
    {
        return new PaymentMethodTable($this->getPaymentMethodQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createViewPaymentMethodForm(PaymentMethodTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ViewPaymentMethodForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\PaymentGui\Communication\Form\DataProvider\ViewPaymentMethodFormDataProvider
     */
    public function createViewPaymentMethodFormDataProvider(): ViewPaymentMethodFormDataProvider
    {
        return new ViewPaymentMethodFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\PaymentGui\Communication\Form\DataProvider\PaymentMethodFormDataProvider
     */
    public function createPaymentMethodFormDataProvider(): PaymentMethodFormDataProvider
    {
        return new PaymentMethodFormDataProvider();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPaymentMethodForm(?PaymentMethodTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(PaymentMethodForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\PaymentGui\Communication\Tabs\PaymentMethodTabs
     */
    public function createPaymentMethodTabs(): PaymentMethodTabs
    {
        return new PaymentMethodTabs();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    public function getPaymentMethodQuery(): SpyPaymentMethodQuery
    {
        return $this->getProvidedDependency(PaymentGuiDependencyProvider::PROPEL_QUERY_PAYMENT_METHOD);
    }

    /**
     * @return \Spryker\Zed\PaymentGui\Dependency\Facade\PaymentGuiToPaymentFacadeInterface
     */
    public function getPaymentFacade(): PaymentGuiToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(PaymentGuiDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(PaymentGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }
}
