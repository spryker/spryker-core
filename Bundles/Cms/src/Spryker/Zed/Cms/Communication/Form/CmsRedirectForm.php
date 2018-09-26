<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 */
class CmsRedirectForm extends AbstractType
{
    public const FIELD_ID_URL_REDIRECT = 'id_url_redirect';
    public const FIELD_FROM_URL = 'from_url';
    public const FIELD_TO_URL = 'to_url';
    public const FIELD_STATUS = 'status';

    public const GROUP_UNIQUE_URL_CHECK = 'unique_url_check';
    public const MAX_COUNT_CHARACTERS_REDIRECT_URL = 255;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                if (array_key_exists(self::FIELD_FROM_URL, $defaultData) === false ||
                    $defaultData[self::FIELD_FROM_URL] !== $form->getData()[self::FIELD_FROM_URL]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_URL_CHECK];
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
            ->addIdRedirectField($builder)
            ->addFromUrlField($builder)
            ->addToUrlField($builder)
            ->addStatusField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdRedirectField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_URL_REDIRECT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFromUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FROM_URL, TextType::class, [
            'label' => 'URL',
            'constraints' => $this->getUrlConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addToUrlField(FormBuilderInterface $builder)
    {
        $constraints = $this->getMandatoryConstraints();
        $constraints[] = new Callback(['callback' => [$this, 'validateUrlRedirectLoop']]);

        $builder->add(self::FIELD_TO_URL, TextType::class, [
            'label' => 'To URL',
            'constraints' => $constraints,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_STATUS,
            ChoiceType::class,
            [
                'label' => 'Redirect status code',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
                'choices' => [
                    Response::HTTP_CREATED => Response::HTTP_CREATED,
                    Response::HTTP_MOVED_PERMANENTLY => Response::HTTP_MOVED_PERMANENTLY,
                    Response::HTTP_FOUND => Response::HTTP_FOUND,
                    Response::HTTP_SEE_OTHER => Response::HTTP_SEE_OTHER,
                    Response::HTTP_TEMPORARY_REDIRECT => Response::HTTP_TEMPORARY_REDIRECT,
                    Response::HTTP_PERMANENTLY_REDIRECT => Response::HTTP_PERMANENTLY_REDIRECT,
                ],
                'placeholder' => 'Please select',
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function getUrlConstraints()
    {
        $urlConstraints = $this->getMandatoryConstraints();

        $urlConstraints[] = new Callback([
            'callback' => function ($url, ExecutionContextInterface $context) {
                $urlTransfer = new UrlTransfer();
                $urlTransfer->setUrl($url);

                if ($this->getFactory()->getUrlFacade()->hasUrlOrRedirectedUrl($urlTransfer)) {
                    $context->addViolation('URL is already used.');
                }
            },
            'groups' => [self::GROUP_UNIQUE_URL_CHECK],
        ]);

        return $urlConstraints;
    }

    /**
     * @return array
     */
    protected function getMandatoryConstraints()
    {
        return [
            $this->createRequiredConstraint(),
            $this->createNotBlankConstraint(),
            $this->createLengthConstraint(self::MAX_COUNT_CHARACTERS_REDIRECT_URL),
            new Callback([
                'callback' => function ($url, ExecutionContextInterface $context) {
                    if ($url[0] !== '/') {
                        $context->addViolation('URL must start with a slash');
                    }
                },
            ]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint()
    {
        return new NotBlank();
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Required
     */
    protected function createRequiredConstraint()
    {
        return new Required();
    }

    /**
     * @param int $max
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createLengthConstraint($max)
    {
        return new Length(['max' => $max]);
    }

    /**
     * @param string $toUrl
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validateUrlRedirectLoop($toUrl, ExecutionContextInterface $context)
    {
        $fromUrl = $context->getRoot()->get(static::FIELD_FROM_URL)->getData();

        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer->setUrl($fromUrl);
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl($toUrl);

        $validationResponse = $this->getFactory()->getUrlFacade()->validateUrlRedirect($urlRedirectTransfer);

        if (!$validationResponse->getIsValid()) {
            $context->addViolation($validationResponse->getError());
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_redirect';
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
