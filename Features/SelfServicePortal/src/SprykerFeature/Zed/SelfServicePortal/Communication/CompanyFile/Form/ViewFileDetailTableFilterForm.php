<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form;

use DateTime;
use Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ViewFileDetailTableFilterForm extends AbstractType
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    /**
     * @var string
     */
    public const OPTION_ENTITY_TYPES = 'entity_types';

    /**
     * @var string
     */
    protected const FIELD_ENTITY_TYPE = 'entityType';

    /**
     * @var string
     */
    protected const FIELD_DATE_FROM = 'dateFrom';

    /**
     * @var string
     */
    protected const FIELD_DATE_TO = 'dateTo';

    /**
     * @var string
     */
    protected const LABEL_ENTITY_TYPE = 'Business entity type';

    /**
     * @var string
     */
    protected const LABEL_DATE_FROM = 'Date From';

    /**
     * @var string
     */
    protected const LABEL_DATE_TO = 'Date To';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ENTITY_TYPE = 'Select business entity type';

    /**
     * @var string
     */
    protected const FIELD_ID_FILE = 'id-file';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_ENTITY_TYPES,
        ]);

        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => FileAttachmentViewDetailTableCriteriaTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addEntityTypeField($builder, $options)
            ->addDateFromField($builder)
            ->addDateToField($builder)
            ->addIdFileField($builder);

        $builder->setMethod(Request::METHOD_GET);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addEntityTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ENTITY_TYPE, ChoiceType::class, [
            'choices' => $options[static::OPTION_ENTITY_TYPES],
            'label' => static::LABEL_ENTITY_TYPE,
            'required' => false,
            'placeholder' => static::PLACEHOLDER_ENTITY_TYPE,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATE_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => static::LABEL_DATE_FROM,
        ]);

        $builder->get(static::FIELD_DATE_FROM)
            ->addModelTransformer(new CallbackTransformer($this->formatDate(), $this->formatDate()));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATE_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => static::LABEL_DATE_TO,
        ]);

        $builder->get(static::FIELD_DATE_TO)
            ->addModelTransformer(new CallbackTransformer($this->formatDate(), $this->formatDate()));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_FILE, HiddenType::class, [
            'required' => true,
            'property_path' => FileAttachmentViewDetailTableCriteriaTransfer::ID_FILE,
        ]);

        return $this;
    }

    /**
     * @return callable
     */
    protected function formatDate(): callable
    {
        return fn ($date) => DateTime::createFromFormat(static::DATE_TIME_FORMAT, $date) ?: null;
    }
}
