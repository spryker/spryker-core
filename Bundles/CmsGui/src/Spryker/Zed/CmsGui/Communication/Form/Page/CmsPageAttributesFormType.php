<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsPageAttributesFormType extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_URL = 'url';
    public const FIELD_LOCALE_NAME = 'localeName';
    public const FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES = 'idCmsPageLocalizedAttributes';

    public const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

    public const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addIdCmsLocalizedAttributes($builder)
            ->addUrlField($builder)
            ->addCmsLocaleNameField($builder);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'updateUrlPrefix']
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function updateUrlPrefix(FormEvent $event)
    {
        $cmsPageAttributesTransfer = $this->getCmsPageAttributesTransfer($event);
        if (!$cmsPageAttributesTransfer) {
            return;
        }

        $url = $cmsPageAttributesTransfer->getUrl();
        if ($cmsPageAttributesTransfer->getUrlPrefix()) {
            $url = preg_replace(
                '#^' . $cmsPageAttributesTransfer->getUrlPrefix() . '#i',
                '',
                $cmsPageAttributesTransfer->getUrl()
            );
        }

        $cmsPageAttributesTransfer->setUrl($url);

        $event->setData($cmsPageAttributesTransfer);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);

        $resolver->setDefaults([
            'constraints' => [
                $this->getFactory()->createUniqueUrlConstraint(),
                $this->getFactory()->createUniqueNameConstraint(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
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
    protected function addCmsLocaleNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_URL, TextType::class, [
            'label' => 'URL',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => static::URL_PATH_PATTERN,
                    'message' => 'Invalid path provided. "Space" and "\" character is not allowed.',
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
    protected function addIdCmsLocalizedAttributes(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer|null
     */
    protected function getCmsPageAttributesTransfer(FormEvent $event)
    {
        return $event->getData();
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_page_attributes';
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
