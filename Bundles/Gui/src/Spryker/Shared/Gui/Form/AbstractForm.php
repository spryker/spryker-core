<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Gui\Form;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated Use `Spryker\Zed\Kernel\Communication\Form\AbstractType` for Zed and `Spryker\Yves\Kernel\Form\AbstractType` for Yves instead.
 */
abstract class AbstractForm implements FormTypeInterface
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see \Symfony\Component\Form\FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array $options The options
     *
     * @return void
     */
    abstract public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|array
     */
    abstract public function populateFormFields();

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    abstract protected function getDataClass();

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        if ($this->getDataClass() instanceof TransferInterface) {
            $resolver->setDefault('data_class', get_class($this->getDataClass()));
        }
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function getEnumSet(array $array)
    {
        return array_combine($array, $array);
    }

    /**
     * Returns the name of the parent type.
     *
     * You can also return a type instance from this method, although doing so
     * is discouraged because it leads to a performance penalty. The support
     * for returning type instances may be dropped from future releases.
     *
     * @return string|\Symfony\Component\Form\FormTypeInterface|null The name of the parent type if any, null otherwise.
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * Builds the form view.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the view.
     *
     * A view of a form is built before the views of the child forms are built.
     * This means that you cannot access child views in this method. If you need
     * to do so, move your logic to {@link finishView()} instead.
     *
     * @see \Symfony\Component\Form\FormTypeExtensionInterface::buildView()
     *
     * @param \Symfony\Component\Form\FormView $view The view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * Finishes the form view.
     *
     * This method gets called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the view.
     *
     * When this method is called, views of the form's children have already
     * been built and finished and can be accessed. You should only implement
     * such logic in this method that actually accesses child views. For everything
     * else you are recommended to implement {@link buildView()} instead.
     *
     * @see \Symfony\Component\Form\FormTypeExtensionInterface::finishView()
     *
     * @param \Symfony\Component\Form\FormView $view The view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }
}
