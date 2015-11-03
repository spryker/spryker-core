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
     * @return $this
     */
    abstract protected function buildFormFields();

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }

    /**
     * @return ConstraintsPlugin
     */
    public function locateConstraint()
    {
        if (is_null($this->constraintsPlugin)) {
            $this->constraintsPlugin = $this->getLocator()->gui()->pluginConstraintsPlugin();
        }

        return $this->constraintsPlugin;
    }

    /**
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
     * @param string $type
     *
     * @return $this
     */
    public function setDefaultDataType($type = null)
    {
        $this->defaultDataType = $type;

        return $this;
    }

    protected function getDefaultDataType()
    {
        return $this->defaultDataType;
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return $this
     *
     * @deprecated this method will become private and will be called in this class ONLY in handleRequest()
     */
    public function init()
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
     * @return $this
     */
    public function handleRequest()
    {
        $this->init();

        return $this->form->handleRequest($this->request);
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->form->isSubmitted();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

    /**
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
     * @return FormErrorIterator
     */
    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @return string
     */
    protected function getFormName()
    {
        return $this->formName;
    }

}
