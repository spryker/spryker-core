<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CmsPageAttributesFormType extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_URL = 'url';
    const FIELD_LOCALE_NAME = 'localeName';
    const FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES = 'idCmsPageLocalizedAttributes';

    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

    const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $uniqueUrlConstraint;

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $uniqueNameConstraint;

    /**
     * @param \Symfony\Component\Validator\Constraint $uniqueUrlConstraint
     * @param \Symfony\Component\Validator\Constraint $uniqueNameConstraint
     */
    public function __construct(
        Constraint $uniqueUrlConstraint,
        Constraint $uniqueNameConstraint
    ) {

        $this->uniqueUrlConstraint = $uniqueUrlConstraint;
        $this->uniqueNameConstraint = $uniqueNameConstraint;
    }

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
                $this->uniqueUrlConstraint,
                $this->uniqueNameConstraint,
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
                new NotBlank()
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
     * @return string
     */
    public function getName()
    {
        return 'cms_page_attributes';
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function getCmsPageAttributesTransfer(FormEvent $event)
    {
        return $event->getData();
    }

}
