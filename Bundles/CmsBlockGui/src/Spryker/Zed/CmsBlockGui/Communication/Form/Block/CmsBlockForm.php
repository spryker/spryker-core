<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\Block;

use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockForm extends AbstractType
{
    public const FIELD_ID_CMS_BLOCK = 'idCmsBlock';
    public const FIELD_STORE_RELATION = 'storeRelation';
    public const FIELD_FK_TEMPLATE = 'fkTemplate';
    public const FIELD_NAME = 'name';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_VALID_FROM = 'validFrom';
    public const FIELD_VALID_TO = 'validTo';

    public const OPTION_TEMPLATE_CHOICES = 'template_choices';

    public const GROUP_UNIQUE_BLOCK_CHECK = 'unique_block_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_TEMPLATE_CHOICES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $formData = $form->getData();

                if (!array_key_exists(static::FIELD_NAME, $defaultData)) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_BLOCK_CHECK];
                }

                if ($defaultData[static::FIELD_NAME] !== $formData[static::FIELD_NAME]) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_BLOCK_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdCmsBlockField($builder)
            ->addStoreRelationForm($builder)
            ->addFkTemplateField($builder, $options)
            ->addNameField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addPluginForms($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsBlockField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CMS_BLOCK, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addFkTemplateField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_FK_TEMPLATE, ChoiceType::class, [
            'label' => 'Template',
            'choices' => array_flip($choices[static::OPTION_TEMPLATE_CHOICES]),
            'choices_as_values' => true,
            'constraints' => [
                new Callback([
                    'callback' => function ($name, ExecutionContextInterface $context) {
                        /** @var \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer */
                        $cmsBlockTransfer = $context->getRoot()->getViewData();

                        if (!$cmsBlockTransfer->getFkTemplate()) {
                            return;
                        }

                        if (!$this->hasTemplateFile($cmsBlockTransfer->getFkTemplate())) {
                            $context->addViolation('Chosen template is not available anymore');
                        }
                    },
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name *',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 255]),
                new Callback([
                    'callback' => function ($name, ExecutionContextInterface $context) {
                        /** @var \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer */
                        $cmsBlockTransfer = $context->getRoot()->getViewData();

                        if ($this->hasExistingBlock($name, $cmsBlockTransfer->getIdCmsBlock())) {
                            $context->addViolation('Block with the same Name already exists.');
                        }
                    },
                    'groups' => [static::GROUP_UNIQUE_BLOCK_CHECK],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                $this->createValidFromRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_VALID_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VALID_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                $this->createValidToFieldRangeConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createValidFromRangeConstraint()
    {
        return new Callback([
            'callback' => function ($dateTimeFrom, ExecutionContextInterface $context) {
                /** @var \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer */
                $cmsBlockTransfer = $context->getRoot()->getData();
                if (!$dateTimeFrom) {
                    return;
                }

                if ($cmsBlockTransfer->getValidTo()) {
                    if ($dateTimeFrom > $cmsBlockTransfer->getValidTo()) {
                        $context->addViolation('Date "Valid from" cannot be later than "Valid to".');
                    }

                    if ($dateTimeFrom == $cmsBlockTransfer->getValidTo()) {
                        $context->addViolation('Date "Valid from" is the same as "Valid to".');
                    }
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createValidToFieldRangeConstraint()
    {
        return new Callback([
            'callback' => function ($dateTimeTo, ExecutionContextInterface $context) {

                /** @var \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer */
                $cmsBlockTransfer = $context->getRoot()->getData();

                if (!$dateTimeTo) {
                    return;
                }

                if ($cmsBlockTransfer->getValidFrom()) {
                    if ($dateTimeTo < $cmsBlockTransfer->getValidFrom()) {
                        $context->addViolation('Date "Valid to" cannot be earlier than "Valid from".');
                    }
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return new DateTime($value);
                }
            },
            function ($value) {
                return $value;
            }
        );
    }

    /**
     * @param string $name
     * @param int|null $idCmsBlock
     *
     * @return bool
     */
    protected function hasExistingBlock($name, $idCmsBlock = null)
    {
        $blockQuery = $this->getFactory()->getCmsBlockQueryContainer()
            ->queryCmsBlockByName($name);

        if ($idCmsBlock) {
            $blockQuery->filterByIdCmsBlock($idCmsBlock, Criteria::NOT_EQUAL);
        }

        return $blockQuery->exists();
    }

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    protected function hasTemplateFile($idCmsBlockTemplate)
    {
        return $this->getFactory()->getCmsBlockFacade()->hasTemplateFileById($idCmsBlockTemplate);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCmsBlockFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_block';
    }
}
