<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentNavigationGui\Communication\ContentNavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig getConfig()
 */
class NavigationContentTermForm extends AbstractType
{
    public const FIELD_NAVIGATION_KEY = 'navigationKey';

    public const LABEL_NAVIGATION = 'Navigation';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                /** @var \Generated\Shared\Transfer\LocalizedContentTransfer $localizedContentTransfer */
                $localizedContentTransfer = $form->getParent()->getData();
                if ($localizedContentTransfer->getFkLocale() === null) {
                    return [Constraint::DEFAULT_GROUP];
                }
                /** @var \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigation */
                $contentNavigation = $form->getNormData();

                foreach ($contentNavigation->toArray() as $field) {
                    if ($field) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
        ]);

        $resolver->setNormalizer('constraints', function () {
            return [$this->getFactory()->createContentNavigationConstraint()];
        });
    }

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
        $builder->add(static::FIELD_NAVIGATION_KEY, Select2ComboBoxType::class, [
            'label' => static::LABEL_NAVIGATION,
            'choices' => $this->getNavigationChoices(),
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
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
