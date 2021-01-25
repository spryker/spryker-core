<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PaymentGui\Communication\Exception\MissingStoreRelationFormTypePluginException;
use Spryker\Zed\PaymentGui\Dependency\Facade\PaymentGuiToPaymentFacadeBridge;

class PaymentGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PAYMENT_METHOD = 'PROPEL_QUERY_PAYMENT_METHOD';

    public const FACADE_PAYMENT = 'FACADE_PAYMENT';

    public const PLUGIN_STORE_RELATION_FORM_TYPE = 'PLUGIN_STORE_RELATION_FORM_TYPE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addPaymentMethodQuery($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addStoreRelationFormTypePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentMethodQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PAYMENT_METHOD, $container->factory(function () {
            return SpyPaymentMethodQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, function (Container $container) {
            return new PaymentGuiToPaymentFacadeBridge(
                $container->getLocator()->payment()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreRelationFormTypePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return $this->getStoreRelationFormTypePlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Zed\ShipmentGui\Communication\Exception\MissingStoreRelationFormTypePluginException
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        throw new MissingStoreRelationFormTypePluginException(
            sprintf(
                'Missing instance of %s! You need to configure StoreRelationFormType ' .
                'in your own PaymentGuiDependencyProvider::getStoreRelationFormTypePlugin() ' .
                'to be able to manage shipment methods.',
                FormTypeInterface::class
            )
        );
    }
}
