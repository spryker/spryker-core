<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 */
class IsOpenForRelationRequestFormType extends AbstractType
{
    /**
     * @var string
     */
    public const IS_OPEN_FOR_RELATION_REQUEST = 'is_open_for_relation_request';

    /**
     * @var string
     */
    protected const LABEL_IS_OPEN_FOR_RELATION_REQUEST = 'Allow merchant relation requests';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsOpenForRelationRequestTypeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIsOpenForRelationRequestTypeField(FormBuilderInterface $builder): void
    {
        $builder
            ->add(static::IS_OPEN_FOR_RELATION_REQUEST, CheckboxType::class, [
                'label' => static::LABEL_IS_OPEN_FOR_RELATION_REQUEST,
                'required' => false,
            ]);
    }
}
