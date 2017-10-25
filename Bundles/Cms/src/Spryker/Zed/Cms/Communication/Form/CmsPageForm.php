<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

class CmsPageForm extends AbstractType
{
    const FIELD_ID_CMS_PAGE = 'idCmsPage';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_URL = 'url';
    const FIELD_CURRENT_TEMPLATE = 'cur_temp';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_ID_URL = 'id_url';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';
    const OPTION_LOCALES_CHOICES = 'locale_choices';
    const GROUP_UNIQUE_URL_CHECK = 'unique_url_check';
    const FIELD_FK_LOCALE = 'fk_locale';
    const FIELD_IS_SEARCHABLE = 'is_searchable';
    const FIELD_LOCALIZED_ATTRIBUTES = 'localized_attributes';

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface $urlFacade
     */
    public function __construct(CmsToUrlInterface $urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_page';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_TEMPLATE_CHOICES);
        $resolver->setRequired(static::OPTION_LOCALES_CHOICES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                if (array_key_exists(static::FIELD_URL, $defaultData) === false ||
                    $defaultData[static::FIELD_URL] !== $form->getData()[static::FIELD_URL]
                ) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_URL_CHECK];
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
            ->addIdCmsPageField($builder)
            ->addIdUrlField($builder)
            ->addCurrentTemplateField($builder)
            ->addFkTemplateField($builder, $options[static::OPTION_TEMPLATE_CHOICES])
            ->addUrlField($builder)
            ->addLocaleField($builder, $options[static::OPTION_LOCALES_CHOICES])
            ->addLocalizedAttributesForm($builder)
            ->addIsSearchableField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsPageField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CMS_PAGE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_URL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCurrentTemplateField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CURRENT_TEMPLATE, HiddenType::class);

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
            'choices' => $choices,
        ]);

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
            'constraints' => $this->getUrlConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $availableLocales
     *
     * @return $this
     */
    protected function addLocaleField(FormBuilderInterface $builder, array $availableLocales)
    {
        $builder->add(static::FIELD_FK_LOCALE, ChoiceType::class, [
            'label' => 'Locale',
            'choices' => $availableLocales,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsSearchableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_SEARCHABLE, CheckboxType::class, [
            'label' => 'Searchable',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesForm(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LOCALIZED_ATTRIBUTES, CmsPageLocalizedAttributesForm::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getUrlConstraints()
    {
        $urlConstraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
            new Callback([
                'methods' => [
                    function ($url, ExecutionContextInterface $context) {
                        $urlTransfer = new UrlTransfer();
                        $urlTransfer->setUrl($url);

                        if ($this->urlFacade->hasUrl($urlTransfer)) {
                            $context->addViolation('URL is already used');
                        }

                        if ($url[0] !== '/') {
                            $context->addViolation('URL must start with a slash');
                        }
                    },
                ],
                'groups' => [static::GROUP_UNIQUE_URL_CHECK],
            ]),
        ];

        return $urlConstraints;
    }
}
