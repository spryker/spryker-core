<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreGui\StoreGuiConfig getConfig()
 */
class CreateStoreForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_ID_STORE = 'id_store';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const LABEL_NAME = 'Name';

    /**
     * @var string
     */
    protected const HELP_NAME_FIELD_IS_READONLY = 'Name is read-only. It cannot be changed once set. Only uppercase letters separated by `_` are allowed.';

    /**
     * @var string
     */
    protected const REGEX_NAME_PATTERN = '/^(?!.*_{2})[A-Z][A-Z_]*[A-Z]$/';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'store';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->executeStoreFormExpanderPlugins($builder, $builder->getData());
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        foreach ($this->getFactory()->getStoreFormViewExpanderPlugins() as $formViewExpanderPlugin) {
            $formViewExpanderPlugin->expandTemplateVariables($view, $form->getData());
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => $this->getNameFieldConstraints(),
            'help' => static::HELP_NAME_FIELD_IS_READONLY,
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getNameFieldConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 255]),
            new Regex([
                'pattern' => static::REGEX_NAME_PATTERN,
            ]),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return $this
     */
    protected function executeStoreFormExpanderPlugins(FormBuilderInterface $builder, StoreTransfer $storeTransfer)
    {
        foreach ($this->getFactory()->getStoreFormExpanderPlugins() as $formPlugin) {
            $formPlugin->expand($builder, $storeTransfer);
        }

        return $this;
    }
}
