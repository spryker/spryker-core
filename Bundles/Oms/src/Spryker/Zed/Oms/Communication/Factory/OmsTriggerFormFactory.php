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
use Spryker\Zed\Oms\Communication\Form\OmsTriggerForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class OmsTriggerFormFactory implements OmsTriggerFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\OrderOmsTriggerFormDataProvider
     */
    public function createOrderOmsTriggerFormDataProvider(): OrderOmsTriggerFormDataProvider
    {
        return new OrderOmsTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\OrderItemOmsTriggerFormDataProvider
     */
    public function createOrderItemOmsTriggerFormDataProvider(): OrderItemOmsTriggerFormDataProvider
    {
        return new OrderItemOmsTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationOmsTriggerFormDataProvider
     */
    public function createReclamationOmsTriggerFormDataProvider(): ReclamationOmsTriggerFormDataProvider
    {
        return new ReclamationOmsTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Form\DataProvider\ReclamationItemOmsTriggerFormDataProvider
     */
    public function createReclamationItemOmsTriggerFormDataProvider(): ReclamationItemOmsTriggerFormDataProvider
    {
        return new ReclamationItemOmsTriggerFormDataProvider();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderOmsTriggerForm(OrderTransfer $orderTransfer, string $event): FormInterface
    {
        $options = $this->createOrderOmsTriggerFormDataProvider()
            ->getOrderOmsTriggerFormOptions($orderTransfer, $event);

        return $this->formFactory->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderItemOmsTriggerForm(ItemTransfer $itemTransfer, string $event): FormInterface
    {
        $options = $this->createOrderItemOmsTriggerFormDataProvider()
            ->getOrderItemOmsTriggerFormOptions($itemTransfer, $event);

        return $this->formFactory->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReclamationOmsTriggerForm(ReclamationTransfer $reclamationTransfer, string $event): FormInterface
    {
        $options = $this->createReclamationOmsTriggerFormDataProvider()
            ->getOrderOmsTriggerFormOptions($reclamationTransfer, $event);

        return $this->formFactory->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     * @param int $idReclamation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReclamationItemOmsTriggerForm(ItemTransfer $itemTransfer, string $event, int $idReclamation): FormInterface
    {
        $options = $this->createReclamationItemOmsTriggerFormDataProvider()
            ->getOrderItemOmsTriggerFormOptions($itemTransfer, $event, $idReclamation);

        return $this->formFactory->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @param mixed|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOmsTriggerForm($data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create(OmsTriggerForm::class, $data, $options);
    }
}
