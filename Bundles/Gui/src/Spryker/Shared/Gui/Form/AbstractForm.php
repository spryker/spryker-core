<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Gui\Form;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Generated\Zed\Ide\AutoCompletion;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Gui\Communication\Plugin\ConstraintsPlugin;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractForm implements FormTypeInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConstraintsPlugin
     */
    protected $constraintsPlugin;

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options
     *
     * @return void
     */
    abstract public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * @return TransferInterface|array
     */
    abstract public function populateFormFields();

    /**
     * @return TransferInterface|null
     */
    abstract protected function getDataClass();

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if ($this->getDataClass() instanceof TransferInterface && !($this->getDataClass() instanceof NullFormTransfer)) {
            $resolver->setDefault('data_class', get_class($this->getDataClass()));
        }
    }

    /**
     * @return ConstraintsPlugin
     */
    public function getConstraints()
    {
        if ($this->constraintsPlugin === null) {
            $this->constraintsPlugin = new ConstraintsPlugin();
        }

        return $this->constraintsPlugin;
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
     * @param Request $request
     *
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        if ($this->request === null) {
            $this->request = (new Pimple())->getApplication()['request'];
        }

        return $this->request;
    }

    /**
     * Returns the name of the parent type.
     *
     * You can also return a type instance from this method, although doing so
     * is discouraged because it leads to a performance penalty. The support
     * for returning type instances may be dropped from future releases.
     *
     * @return string|null|FormTypeInterface The name of the parent type if any, null otherwise.
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
     * @see FormTypeExtensionInterface::buildView()
     *
     * @param FormView $view The view
     * @param FormInterface $form The form
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
     * @see FormTypeExtensionInterface::finishView()
     *
     * @param FormView $view The view
     * @param FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

}
