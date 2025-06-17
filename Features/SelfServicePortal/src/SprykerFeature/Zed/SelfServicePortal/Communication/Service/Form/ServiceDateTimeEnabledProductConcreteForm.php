<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ServiceDateTimeEnabledProductConcreteForm extends AbstractType
{
    /**
     * @uses \Generated\Shared\Transfer\ProductConcreteTransfer::IS_SERVICE_DATE_TIME_ENABLED
     *
     * @var string
     */
    public const FIELD_IS_SERVICE_DATE_TIME_ENABLED = 'isServiceDateTimeEnabled';

    /**
     * @var string
     */
    protected const LABEL_IS_SERVICE_DATE_TIME_ENABLED = 'Enable Service Date and Time';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsServiceDateTimeEnabledField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsServiceDateTimeEnabledField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_SERVICE_DATE_TIME_ENABLED, CheckboxType::class, [
            'label' => static::LABEL_IS_SERVICE_DATE_TIME_ENABLED,
            'required' => false,
        ]);

        return $this;
    }
}
