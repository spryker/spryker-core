<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Gui\Communication\Plugin\ConstraintsPlugin;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use UserSprykerFeature/Zed/Gui/Communication/AbstractFormType instead
 */
abstract class AbstractForm
{
    use FormBuilderTrait;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var string
     */
    protected $defaultDataType;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $formName = 'form';

    /**
     * @var ConstraintsPlugin
     */
    protected $constraintsPlugin;

    /**
     * Prepares form
     *
     * @deprecated use AbstractFormType instead
     *
     * @return $this
     */
    abstract protected function buildFormFields();

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
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
     * @deprecated use AbstractFormType instead
     *
     * @return $this
     */
    protected function injectDependencies()
    {
        $app = $this->getLocator()
            ->application()
            ->pluginPimple()
            ->getApplication()
        ;

        $this->request = $app['request'];
        $this->formFactory = $app['form.factory'];

        $this->form = $this->formFactory->createNamed(
            $this->getFormName(),
            'form',
            $this->getDefaultDataType(),
            $this->options
        );

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $type
     *
     * @return $this
     */
    public function setDefaultDataType($type = null)
    {
        $this->defaultDataType = $type;

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return string
     */
    protected function getDefaultDataType()
    {
        return $this->defaultDataType;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return $this
     */
    protected function init()
    {
        if (!$this->initialized) {
            $this->initialized = true;

            $this->injectDependencies()->buildFormFields();

            $data = $this->populateFormFields();
            $this->setData($data);
        }

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        if (!$this->initialized) {
            $this->init();
        }
        $this->form->setData($data);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return mixed
     */
    public function createView()
    {
        if ($this->getDefaultDataType() instanceof AbstractTransfer) {
            $this->setData($this->getData());
        }

        return $this->form->createView();
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return $this
     */
    public function handleRequest()
    {
        $this->init();

        return $this->form->handleRequest($this->request);
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->form->isSubmitted();
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return mixed
     */
    public function getData()
    {
        $data = $this->form->getData();

        if (is_null($this->getDefaultDataType()) && $this->getDefaultDataType() instanceof AbstractTransfer) {
            return $this->getDefaultDataType()->fromArray($data, true);
        }

        return $data;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return FormErrorIterator
     */
    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @return string
     */
    protected function getFormName()
    {
        return $this->formName;
    }

}
