<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationNodeFormType extends AbstractType
{

    const FIELD_NODE_TYPE = 'node_type';
    const FIELD_NAVIGATION_NODE_LOCALIZED_ATTRIBUTES = 'navigation_node_localized_attributes';
    const FIELD_IS_ACTIVE = 'is_active';

    const NODE_TYPE_CATEGORY = 'category';
    const NODE_TYPE_CMS_PAGE = 'cms_page';
    const NODE_TYPE_LINK = 'link';
    const NODE_TYPE_EXTERNAL_URL = 'external_url';

    /**
     * @var \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType
     */
    protected $navigationNodeLocalizedAttributesFormType;

    /**
     * @param \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType $navigationNodeLocalizedAttributesFormType
     */
    public function __construct(NavigationNodeLocalizedAttributesFormType $navigationNodeLocalizedAttributesFormType)
    {
        $this->navigationNodeLocalizedAttributesFormType = $navigationNodeLocalizedAttributesFormType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'navigation_node';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => NavigationNodeTransfer::class,
            'required' => false,
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
            ->addNodeTypeField($builder)
            ->addNavigationNodeLocalizedAttributesForms($builder)
            ->addIsActiveField($builder);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $this->unsetLocalizedUrls($event, $event->getData());
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNodeTypeField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NODE_TYPE, ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'Label',
                'choices' => [
                    'Category' => self::NODE_TYPE_CATEGORY,
                    'CMS page' => self::NODE_TYPE_CMS_PAGE,
                    'Link' => self::NODE_TYPE_LINK,
                    'External URL' => self::NODE_TYPE_EXTERNAL_URL,
                ],
                'choices_as_values' => true,
                'choice_attr' => [
                    'Category' => ['data-url' => '/search-for-category'],
                    'CMS page' => ['data-url' => '/search-for-cms'],
                    'Link' => [],
                    'External URL' => [],
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNavigationNodeLocalizedAttributesForms(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NAVIGATION_NODE_LOCALIZED_ATTRIBUTES, CollectionType::class, [
                'type' => $this->navigationNodeLocalizedAttributesFormType,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IS_ACTIVE, CheckboxType::class, [
                'label' => 'Active',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function unsetLocalizedUrls(FormEvent $event, NavigationNodeTransfer $navigationNodeTransfer)
    {
        if ($navigationNodeTransfer->getNodeType() === self::NODE_TYPE_CATEGORY || $navigationNodeTransfer->getNodeType() === self::NODE_TYPE_CMS_PAGE) {
            return;
        }

        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $navigationNodeLocalizedAttributesTransfer) {
            $navigationNodeLocalizedAttributesTransfer->setFkUrl(null);
        }

        $event->setData($navigationNodeTransfer);
    }

}
