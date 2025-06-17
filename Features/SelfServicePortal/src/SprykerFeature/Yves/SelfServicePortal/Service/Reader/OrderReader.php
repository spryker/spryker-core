<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Sales\SalesClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_ITEM_NOT_FOUND = 'self_service_portal.service.update_scheduled_time.error.order_item_not_found';

    /**
     * @var \Spryker\Client\Sales\SalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param \Spryker\Client\Sales\SalesClientInterface $salesClient
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryStorageClient
     * @param string $locale
     */
    public function __construct(
        SalesClientInterface $salesClient,
        CustomerClientInterface $customerClient,
        GlossaryStorageClientInterface $glossaryStorageClient,
        string $locale
    ) {
        $this->salesClient = $salesClient;
        $this->customerClient = $customerClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->locale = $locale;
    }

    /**
     * @param int $idSalesOrder
     * @param string $orderItemUuid
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItem(int $idSalesOrder, string $orderItemUuid): ItemTransfer
    {
        if (!$orderItemUuid || !$idSalesOrder) {
            throw new NotFoundHttpException(
                $this->glossaryStorageClient->translate(
                    static::GLOSSARY_KEY_ORDER_ITEM_NOT_FOUND,
                    $this->locale,
                    ['%uuid%' => $orderItemUuid],
                ),
            );
        }

        /**
         * @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
         */
        $customerTransfer = $this->customerClient->getCustomer();
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient->getOrderDetails($orderTransfer);

        foreach ($orderTransfer->getItems() as $item) {
            if ($item instanceof ItemTransfer && $item->getUuid() === $orderItemUuid) {
                return $item;
            }
        }

        throw new NotFoundHttpException(
            $this->glossaryStorageClient->translate(
                static::GLOSSARY_KEY_ORDER_ITEM_NOT_FOUND,
                $this->locale,
                ['%uuid%' => $orderItemUuid],
            ),
        );
    }
}
