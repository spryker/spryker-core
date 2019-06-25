<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class LocalizedContentForm extends AbstractType
{
    public const FIELD_FK_LOCALE = 'fk_locale';
    public const FIELD_NAME = 'locale_name';

    public const FIELD_PARAMETERS = 'parameters';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(ContentForm::OPTION_CONTENT_ITEM_FORM_PLUGIN);

        $resolver->setDefaults([
            'required' => true,
            'validation_groups' => function (FormInterface $form) {
                $submittedData = $form->getData();

                if ($submittedData->getFkLocale() !== null) {
                    return null;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkLocale($builder);
        $this->addLocaleName($builder);
        $this->addParameterCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocale(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addParameterCollection(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface $contentPlugin
         */
        $contentPlugin = $options[ContentForm::OPTION_CONTENT_ITEM_FORM_PLUGIN];

        $builder->add(
            static::FIELD_PARAMETERS,
            $contentPlugin->getForm(),
            [
                'error_bubbling' => false,
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                ],
            ]
        );

        $builder->get(static::FIELD_PARAMETERS)
            ->addModelTransformer(new CallbackTransformer(
                function (?string $params = null) use ($contentPlugin) {
                    $params = $this->getFactory()->getUtilEncoding()->decodeJson((string)$params, true);

                    return $contentPlugin->getTransferObject($params);
                },
                function (TransferInterface $transfer) {
                    $arrayFilter = function ($input) use (&$arrayFilter) {
                        foreach ($input as &$value) {
                            if (is_array($value)) {
                                $value = $arrayFilter($value);
                            }
                        }

                        return array_filter($input);
                    };
                    $parameters = $arrayFilter($transfer->toArray());

                    return (!empty($parameters)) ? $this->getFactory()->getUtilEncoding()->encodeJson($transfer->toArray()) : null;
                }
            ));

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'localized-content';
    }
}
