<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use Generated\Shared\Transfer\LocaleTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPageAttributesFormType extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_URL = 'url';
    const FIELD_LOCALE_NAME = 'localeName';
    const FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES = 'idCmsPageLocalizedAttributes';

    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

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
            ->addUrlField($builder, $options)
            ->addCmsLocaleNameField($builder)
            ->addFieldLocalName($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
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
     * @param array $options
     *
     * @return $this
     */
    protected function addUrlField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_URL, TextType::class, [
            'label' => 'URL *'
        ]);

        $builder->get(static::FIELD_URL)
            ->addModelTransformer($this->createUrlFieldDataTransformer($options[static::OPTION_AVAILABLE_LOCALES]));

        return $this;
    }

    /**
     * @param array|LocaleTransfer[] $availableLocales
     *
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createUrlFieldDataTransformer(array $availableLocales)
    {
        return new CallbackTransformer(
            function ($value) use ($availableLocales) {
                $languageMatchPatternPart = $this->createLanguageMatchPatternPart($availableLocales);
                return preg_replace('#^/(' . $languageMatchPatternPart . ')/#i', '', $value);
            },
            function($value) {
                return $value;
            }
        );
    }

    /**
     * @param array|LocaleTransfer[] $availableLocales
     *
     * @return array
     */
    protected function createLanguageMatchPatternPart(array $availableLocales)
    {
        $languageCodeList = [];
        foreach ($availableLocales as $localeTransfer) {
            $languageCodeList[] = substr($localeTransfer->getLocaleName(), 0, 2);
        }

        return implode('|', $languageCodeList);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldLocalName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class);

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
     * @return string
     */
    public function getName()
    {
        return 'cms_page_attributes';
    }
}
