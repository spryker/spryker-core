<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\NavigationGui\Communication\Form\Constraint\CategoryUrlConstraint;
use Spryker\Zed\NavigationGui\Communication\Form\Constraint\CmsPageUrlConstraint;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 */
class NavigationNodeLocalizedAttributesFormType extends AbstractType
{
    public const FIELD_TITLE = 'title';
    public const FIELD_FK_LOCALE = 'fk_locale';
    public const FIELD_LINK = 'link';
    public const FIELD_EXTERNAL_URL = 'external_url';
    public const FIELD_CMS_PAGE_URL = 'cms_page_url';
    public const FIELD_CATEGORY_URL = 'category_url';
    public const FIELD_CSS_CLASS = 'css_class';

    public const GROUP_CMS = 'cms_page';
    public const GROUP_CATEGORY = 'category';
    public const GROUP_LINK = 'link';
    public const GROUP_EXTERNAL_URL = 'external_url';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => NavigationNodeLocalizedAttributesTransfer::class,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                $nodeType = $form->getParent()
                    ->getParent()
                    ->get(NavigationNodeFormType::FIELD_NODE_TYPE)
                    ->getData();

                if ($nodeType) {
                    return [Constraint::DEFAULT_GROUP, $nodeType];
                }

                return [Constraint::DEFAULT_GROUP];
            },
            'constraints' => [
                new CmsPageUrlConstraint([
                    CmsPageUrlConstraint::OPTION_URL_FACADE => $this->getFactory()->getUrlFacade(),
                    'groups' => [self::GROUP_CMS],
                ]),
                new CategoryUrlConstraint([
                    CategoryUrlConstraint::OPTION_URL_FACADE => $this->getFactory()->getUrlFacade(),
                    'groups' => [self::GROUP_CATEGORY],
                ]),
            ],
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
            ->addTitleField($builder)
            ->addLinkField($builder)
            ->addExternalUrlField($builder)
            ->addCmsPageUrlField($builder)
            ->addCategoryUrlField($builder)
            ->addCssClassField($builder)
            ->addFkLocaleField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_TITLE, TextType::class, [
                'label' => 'Title',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCmsPageUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CMS_PAGE_URL, AutosuggestType::class, [
            'label' => 'CMS page URL',
            'attr' => [
                'placeholder' => 'Type 3 letters to search by CMS page name.',
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => [self::GROUP_CMS],
                ]),
            ],
        ])->addModelTransformer(new CallbackTransformer(
            [$this, 'transformCmsPageUrlField'],
            [$this, 'reverseTransformCmsPageUrlField']
        ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CATEGORY_URL, AutosuggestType::class, [
            'label' => 'Category URL',
            'attr' => [
                'placeholder' => 'Type 3 letters to search by category name.',
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => [self::GROUP_CATEGORY],
                ]),
            ],
        ])->addModelTransformer(new CallbackTransformer(
            [$this, 'transformCategoryUrlField'],
            [$this, 'reverseTransformCategoryUrlField']
        ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLinkField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_LINK, TextType::class, [
                'label' => 'Link',
                'attr' => [
                    'placeholder' => '/',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::GROUP_LINK],
                    ]),
                    new Regex([
                        'pattern' => '/^\/.*/i',
                        'groups' => [self::GROUP_LINK],
                        'message' => 'Links should start with "/".',
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
    protected function addExternalUrlField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_EXTERNAL_URL, TextType::class, [
                'label' => 'External URL',
                'attr' => [
                    'placeholder' => 'http://',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::GROUP_EXTERNAL_URL],
                    ]),
                    new Url([
                        'groups' => [self::GROUP_EXTERNAL_URL],
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
    protected function addCssClassField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_CSS_CLASS, TextType::class, [
                'label' => 'Custom CSS class',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    public function transformCmsPageUrlField(NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        if (!$localizedAttributesTransfer->getFkUrl()) {
            return $localizedAttributesTransfer;
        }

        $urlTransfer = $this->findUrlTransferById($localizedAttributesTransfer->getFkUrl());
        if ($urlTransfer && $urlTransfer->getFkResourcePage()) {
            $localizedAttributesTransfer->setCmsPageUrl($urlTransfer->getUrl());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    public function reverseTransformCmsPageUrlField(NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        if (!$localizedAttributesTransfer->getCmsPageUrl()) {
            return $localizedAttributesTransfer;
        }

        $urlTransfer = $this->findUrlTransferByUrl($localizedAttributesTransfer->getCmsPageUrl());
        if ($urlTransfer) {
            $localizedAttributesTransfer->setFkUrl($urlTransfer->getIdUrl());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    public function transformCategoryUrlField(NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        if (!$localizedAttributesTransfer->getFkUrl()) {
            return $localizedAttributesTransfer;
        }

        $urlTransfer = $this->findUrlTransferById($localizedAttributesTransfer->getFkUrl());
        if ($urlTransfer && $urlTransfer->getFkResourceCategorynode()) {
            $localizedAttributesTransfer->setCategoryUrl($urlTransfer->getUrl());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    public function reverseTransformCategoryUrlField(NavigationNodeLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        if (!$localizedAttributesTransfer->getCategoryUrl()) {
            return $localizedAttributesTransfer;
        }

        $urlTransfer = $this->findUrlTransferByUrl($localizedAttributesTransfer->getCategoryUrl());
        if ($urlTransfer) {
            $localizedAttributesTransfer->setFkUrl($urlTransfer->getIdUrl());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param int $idUrl
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findUrlTransferById($idUrl)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($idUrl);

        return $this->getFactory()->getUrlFacade()->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findUrlTransferByUrl($url)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        return $this->getFactory()->getUrlFacade()->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'navigation_node_localized_attributes';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
