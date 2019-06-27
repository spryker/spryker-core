<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Factory;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Oms\Communication\Form\DataProvider\OrderItemOmsTriggerFormDataProvider;
use Spryker\Zed\Oms\Communication\Form\DataProvider\OrderOmsTriggerFormDataProvider;
use Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationItemOmsTriggerFormDataProvider;
use Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationOmsTriggerFormDataProvider;
use Symfony\Component\Form\FormInterface;

interface OmsTriggerFormFactoryInterface
{
    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\OrderOmsTriggerFormDataProvider
     */
    public function createOrderOmsTriggerFormDataProvider(): OrderOmsTriggerFormDataProvider;

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\OrderItemOmsTriggerFormDataProvider
     */
    public function createOrderItemOmsTriggerFormDataProvider(): OrderItemOmsTriggerFormDataProvider;

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationOmsTriggerFormDataProvider
     */
    public function createReclamationOmsTriggerFormDataProvider(): ReclamationOmsTriggerFormDataProvider;

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationItemOmsTriggerFormDataProvider
     */
    public function createReclamationItemOmsTriggerFormDataProvider(): ReclamationItemOmsTriggerFormDataProvider;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderOmsTriggerForm(OrderTransfer $orderTransfer, string $event): FormInterface;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderItemOmsTriggerForm(ItemTransfer $itemTransfer, string $event): FormInterface;

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReclamationOmsTriggerForm(ReclamationTransfer $reclamationTransfer, string $event): FormInterface;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     * @param int $idReclamation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReclamationItemOmsTriggerForm(ItemTransfer $itemTransfer, string $event, int $idReclamation): FormInterface;

    /**
     * @param mixed|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOmsTriggerForm($data = null, array $options = []): FormInterface;
}
