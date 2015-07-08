<?php

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Product\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractForm
{

    /**
     * @var Form
     */
    protected $form = null;

    protected $validation_rules = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var ConstraintBuilder
     */
    protected $constraintBuilder;

    /**
     * @return $this
     */
    public function init()
    {
        $this->injectDependencies();
        $this->buildFormFields();
        $data = $this->populateFormFields();
        $this->setData($data);
        return $this;
    }

    protected function injectDependencies()
    {
        $app = $this->getLocator()
            ->application()
            ->pluginPimple()
            ->getApplication();

        $this->request = $app['request'];
        $this->formFactory = $app['form.factory'];

        $this->constraintBuilder = new ConstraintBuilder(); // TODO Hard Dependency to a class

        $this->form = $this->formFactory->create();
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    abstract protected function buildFormFields();

    abstract protected function populateFormFields();

    public function setData($data)
    {
        $this->form->setData($data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return $this->form->createView();
    }

    public function processRequest($request)
    {

    }

    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @return bool
     */
    public function isSubmitted(){
        return $this->form->isSubmitted();
    }

    public function handleRequest()
    {
        $this->form->handleRequest($this->request);
    }

    public function isValid()
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

    public function getData()
    {
        return $this->form->getData();
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addText($name, $options = array())
    {
        $this->add($name, 'text', $options);
        return $this;
    }

    public function add($name, $type, $options = array())
    {
        if ($options instanceof ConstraintBuilder) {
            $options = ['constraints' => $options->getConstraints()];
        }

        return $this->form->add($name, $type, $options);
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addTextarea($name, $options = array())
    {
        $this->add($name, 'textarea', $options);
        return $this;
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addEmail($name, $options = array())
    {
        $this->add($name, 'email', $options);
        return $this;
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addInteger($name, $options = array())
    {
        $this->add($name, 'integer', $options);
        return $this;
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addAutosugest($name, $options = array())
    {
        $this->add($name, new AutosuggestType(), $options);
        return $this;
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addChoice($name, $options = array())
    {
        $this->add($name, 'choice', $options);
        return $this;
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return $this
     */
    public function addHidden($name, $options = array())
    {
        $this->add($name, 'hidden', $options);
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addSubmit($name = 'submit', $options = array())
    {
        $this->add($name, 'submit', $options);
        return $this;
    }

    /**
     * @return ConstraintBuilder
     */
    protected function getConstraintBuilder()
    {
        return $this->constraintBuilder;
    }
}
