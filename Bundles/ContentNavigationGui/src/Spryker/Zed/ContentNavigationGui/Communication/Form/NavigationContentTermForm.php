<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ContentNavigationGui\Communication\ContentNavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig getConfig()
 */
class NavigationContentTermForm extends AbstractType
{
    public const FIELD_NAVIGATION_KEY = 'navigationKey';

    public const LABEL_NAVIGATION = 'Navigation';

    protected const CONSTRAINTS = 'constraints';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'navigation';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNavigationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNavigationField(FormBuilderInterface $builder)
    {
        $builderConstraints = $builder->getOption(static::CONSTRAINTS);
        $builder->add(static::FIELD_NAVIGATION_KEY, Select2ComboBoxType::class, [
            'label' => static::LABEL_NAVIGATION,
            'choices' => $this->getNavigationChoices(),
            'multiple' => false,
            'required' => true,
            static::CONSTRAINTS => !$this->hasNotBlankConstraint($builderConstraints) ? new NotBlank() : [],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Validator\Constraint[] $builderConstraints
     *
     * @return bool
     */
    protected function hasNotBlankConstraint(array $builderConstraints): bool
    {
        $hasNotBlank = false;
        foreach ($builderConstraints as $builderConstraint) {
            if ($builderConstraint instanceof NotBlank) {
                return true;
            }
        }

        return $hasNotBlank;
    }

    /**
     * @return string[]
     */
    protected function getNavigationChoices(): array
    {
        return $this->getFactory()
            ->createNavigationContentTermFormDataProvider()
            ->getNavigationChoices();
    }
}
