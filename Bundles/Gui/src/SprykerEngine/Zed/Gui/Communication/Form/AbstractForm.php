<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Gui\Communication\Plugin\ConstraintsPlugin;
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
     * @param array                $options The options
     *
     * @return void
     */
    abstract public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * @return array|TransferInterface
     */
    abstract public function populateFormFields();

    /**
     * @return null|TransferInterface
     */
    abstract protected function getDataClass();

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if (!($this->getDataClass() instanceof TransferInterface)) {
            return;
        }

        $resolver->setDefault('data_class', get_class($this->getDataClass()));
    }

    /**
     * @return ConstraintsPlugin
     */
    public function getConstraints()
    {
        if (is_null($this->constraintsPlugin)) {
            $this->constraintsPlugin = $this->getLocator()->gui()->pluginConstraintsPlugin();
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
            $this->request = $this->getLocator()
                ->application()
                ->pluginPimple()
                ->getApplication()['request']
            ;
        }

        return $this->request;
    }

    /**
     * Locator can be used here, but no form type class should use it. Keep it PRIVATE
     *
     * @return AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
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
     * @param FormView      $view    The view
     * @param FormInterface $form    The form
     * @param array         $options The options
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
     * @param FormView      $view    The view
     * @param FormInterface $form    The form
     * @param array         $options The options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

}
