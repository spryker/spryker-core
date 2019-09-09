<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Builder;

use Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface;

class OmsTriggerFormCollectionBuilder implements OmsTriggerFormCollectionBuilderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface
     */
    protected $omsTriggerFormFactory;

    /**
     * @param \Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface $omsTriggerFormFactory
     */
    public function __construct(OmsTriggerFormFactoryInterface $omsTriggerFormFactory)
    {
        $this->omsTriggerFormFactory = $omsTriggerFormFactory;
    }

    /**
     * @param string $redirectUrl
     * @param string[] $events
     * @param int $idSalesOrder
     * @param string $submitButtonExtraClass
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function buildOrderOmsTriggerFormCollection(string $redirectUrl, array $events, int $idSalesOrder, string $submitButtonExtraClass = ''): array
    {
        $orderOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getOrderOmsTriggerForm($redirectUrl, $event, $idSalesOrder, $submitButtonExtraClass)
                ->createView();
        }

        return $orderOmsTriggerFormCollection;
    }

    /**
     * @param string $redirectUrl
     * @param array $eventsGroupedByItem
     * @param int $idSalesOrderItem
     *
     * @return array
     */
    public function buildOrderItemOmsTriggerFormCollection(string $redirectUrl, array $eventsGroupedByItem, int $idSalesOrderItem): array
    {
        $orderItemOmsTriggerFormCollection = [];

        foreach ($eventsGroupedByItem as $event) {
            $orderItemOmsTriggerFormCollection[$event] = $this->omsTriggerFormFactory
                ->getOrderItemOmsTriggerForm($redirectUrl, $event, $idSalesOrderItem)
                ->createView();
        }

        return $orderItemOmsTriggerFormCollection;
    }
}
