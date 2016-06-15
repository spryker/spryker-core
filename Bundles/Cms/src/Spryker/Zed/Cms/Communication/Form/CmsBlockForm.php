<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
    const FIELD_FK_PAGE = 'fkPage';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_NAME = 'name';
    const FIELD_TYPE = 'type';
    const FIELD_VALUE = 'value';
    const FIELD_CURRENT_TEMPLATE = 'cur_temp';
    const FIELD_IS_ACTIVE = 'is_active';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';

    const GROUP_UNIQUE_BLOCK_CHECK = 'unique_block_check';

    const TYPE_STATIC = 'static';
    const TYPE_CATEGORY = 'category';
    const TYPE_PRODUCT = 'product';

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_block';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_TEMPLATE_CHOICES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $formData = $form->getData();

                if (!array_key_exists(self::FIELD_NAME, $defaultData) ||
                    !array_key_exists(self::FIELD_TYPE, $defaultData) ||
                    !array_key_exists(self::FIELD_VALUE, $defaultData)
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_BLOCK_CHECK];
                }

                if ($defaultData[self::FIELD_NAME] !== $formData[self::FIELD_NAME] ||
                    $defaultData[self::FIELD_TYPE] !== $formData[self::FIELD_TYPE] ||
                    (int)$defaultData[self::FIELD_VALUE] !== (int)$formData[self::FIELD_VALUE]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_BLOCK_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            }
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
        $builder->add(self::FIELD_ID_CMS_BLOCK, 'hidden')
            ->add(self::FIELD_CURRENT_TEMPLATE, 'hidden')
            ->add(self::FIELD_FK_PAGE, 'hidden')
            ->add(self::FIELD_FK_TEMPLATE, 'choice', [
                'label' => 'Template',
                'choices' => $options[self::OPTION_TEMPLATE_CHOICES],
            ])
            ->add(self::FIELD_NAME, 'text', [
                'label' => 'Name',
                'constraints' => $this->getBlockConstraints(),
            ])
            ->add(self::FIELD_TYPE, 'choice', [
                'label' => 'Type',
                'choices' => [
                    self::TYPE_STATIC => 'Static',
                    self::TYPE_CATEGORY => 'Category',
                    self::TYPE_PRODUCT => 'Product',
                ],
            ])
            ->add(self::FIELD_SELECT_VALUE, 'text', [
                'label' => 'Value',
            ])
            ->add(self::FIELD_VALUE, 'hidden', [
                'label' => 'Value',
            ]);
    }

    /**
     * @return array
     */
    protected function getBlockConstraints()
    {
        $blockConstraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];

        $blockConstraints[] = new Callback([
            'methods' => [
                function ($name, ExecutionContextInterface $context) {
                    $formData = $context->getRoot()->getViewData();

                    if ($this->hasExistingBlock($name, $formData[self::FIELD_TYPE], $formData[self::FIELD_VALUE])) {
                        $context->addViolation('Block name with same Type and Value already exists.');
                    }
                },
            ],
            'groups' => [self::GROUP_UNIQUE_BLOCK_CHECK]
        ]);

        return $blockConstraints;
    }

    /**
     * @param string $name
     * @param string $type
     * @param int $value
     *
     * @return bool
     */
    protected function hasExistingBlock($name, $type, $value)
    {
        $blockEntity = $this->cmsQueryContainer
            ->queryBlockByNameAndTypeValue($name, $type, $value)
            ->findOne();

        $hasBlock = ($blockEntity !== null);

        return $hasBlock;
    }

}
