<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Block;

use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsBlockQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CmsBlockForm extends AbstractType
{

    const FIELD_SELECT_VALUE = 'selectValue';
    const FIELD_ID_CMS_BLOCK = 'idCmsBlock';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_NAME = 'name';
    const FIELD_IS_ACTIVE = 'is_active';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';

    const GROUP_UNIQUE_BLOCK_CHECK = 'unique_block_check';

    const TYPE_STATIC = 'static';
    const TYPE_CATEGORY = 'category';
    const TYPE_PRODUCT = 'product';

    /**
     * @var CmsGuiToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param CmsGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct(CmsGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_block';
    }

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
            ->addFkTemplateField($builder, $options)
            ->addNameField($builder)
            ->addValueField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsBlockField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CMS_BLOCK, 'hidden');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addFkTemplateField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_FK_TEMPLATE, 'choice', [
            'label' => 'Template',
            'choices' => $choices[static::OPTION_TEMPLATE_CHOICES],
        ]);


        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, 'text', [
            'label' => 'Name',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 255]),
                new Callback([
                    'methods' => [
                        function ($name, ExecutionContextInterface $context) {
                            if ($this->hasExistingBlock($name)) {
                                $context->addViolation('Block with the same Name already exists.');
                            }
                        },
                    ],
                    'groups' => [static::GROUP_UNIQUE_BLOCK_CHECK],
                ])
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder)
    {
//        $builder->add(static::FIELD_VALUE, 'text', [
//            'label' => 'Value',
//        ]);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasExistingBlock($name)
    {
        $blockEntity = $this->cmsBlockQueryContainer
            ->queryCmsBlockByName($name)
            ->findOne();

        $hasBlock = ($blockEntity !== null);

        return $hasBlock;
    }

}
