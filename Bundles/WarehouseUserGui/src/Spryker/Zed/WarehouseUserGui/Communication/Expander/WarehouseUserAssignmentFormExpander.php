<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Expander;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class WarehouseUserAssignmentFormExpander implements WarehouseUserAssignmentFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_IS_WAREHOUSE_USER = 'is_warehouse_user';

    /**
     * @var string
     */
    protected const LABEL_IS_WAREHOUSE_USER = 'This user is a warehouse user';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH_IS_WAREHOUSE_USER = '@WarehouseUserGui/_partials/user-form-is-warehouse-user-field.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function expandForm(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_IS_WAREHOUSE_USER, CheckboxType::class, [
            'label' => static::LABEL_IS_WAREHOUSE_USER,
            'required' => false,
            'attr' => [
                'template_path' => static::TEMPLATE_PATH_IS_WAREHOUSE_USER,
            ],
        ]);
    }
}
