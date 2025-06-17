<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspServiceCancelForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ITEM_UUID = 'itemUuid';

    /**
     * @var string
     */
    public const FIELD_ID_SALES_ORDER = 'idSalesOrder';

    /**
     * @var string
     */
    protected const FORM_NAME = 'sspServiceCancelForm';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addItemUuidField($builder)
            ->addIdSalesOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemUuidField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ITEM_UUID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdSalesOrderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_SALES_ORDER, HiddenType::class);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }
}
