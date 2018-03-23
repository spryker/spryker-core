<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\OrderSource\OrderSourceListType;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToSalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderSourceListDataProvider implements FormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToSalesQueryContainerInterface $customerQueryContainer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        ManualOrderEntryGuiToSalesQueryContainerInterface $customerQueryContainer,
        Request $request
    ) {
        $this->salesQueryContainer = $customerQueryContainer;
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer)
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            OrderSourceListType::OPTION_ORDER_SOURCE_ARRAY => $this->getOrderSourceList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($quoteTransfer)
    {
        if ($this->request->getMethod() === $this->request::METHOD_GET
            && $this->request->query->get(OrderSourceListType::FIELD_ORDER_SOURCE)
        ) {
            $quoteTransfer->setIdOrderSource($this->request->query->get(OrderSourceListType::FIELD_ORDER_SOURCE));
        }

        if ($quoteTransfer->getOrderSource() === null) {
            $quoteTransfer->setOrderSource(new OrderSourceTransfer());
        }

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    protected function getOrderSourceList()
    {
        $orderSources = $this->salesQueryContainer
            ->queryOrderSource()
            ->find();

        $orderSourceList = [];

        /** @var \Orm\Zed\Sales\Persistence\SpyOrderSource $orderSource */
        foreach ($orderSources as $orderSource) {
            $orderSourceList[$orderSource->getIdOrderSource()] = $orderSource->getOrderSourceName();
        }

        return $orderSourceList;
    }
}
