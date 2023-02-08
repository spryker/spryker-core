<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\WarehouseUserGui\Communication\WarehouseUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\WarehouseUserGui\WarehouseUserGuiConfig getConfig()
 */
class WarehouseUserForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_USER_UUID = 'userUuid';

    /**
     * @var string
     */
    protected const FIELD_UUIDS_WAREHOUSES_TO_ASSIGN = 'uuidsWarehousesToAssign';

    /**
     * @var string
     */
    protected const FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN = 'uuidsWarehousesToDeassign';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'warehouseUser';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addUserUuidField($builder)
            ->addUuidsWarehousesToAssignField($builder)
            ->addUuidsWarehousesToDeassignField($builder)
            ->addModelTransformers($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addUserUuidField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_USER_UUID,
            HiddenType::class,
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addUuidsWarehousesToAssignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_UUIDS_WAREHOUSES_TO_ASSIGN,
            HiddenType::class,
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addUuidsWarehousesToDeassignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN,
            HiddenType::class,
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addModelTransformers(FormBuilderInterface $builder)
    {
        $builder->get(static::FIELD_UUIDS_WAREHOUSES_TO_ASSIGN)
            ->addModelTransformer($this->getFactory()->createArrayToStringModelTransformer());
        $builder->get(static::FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN)
            ->addModelTransformer($this->getFactory()->createArrayToStringModelTransformer());

        return $this;
    }
}
