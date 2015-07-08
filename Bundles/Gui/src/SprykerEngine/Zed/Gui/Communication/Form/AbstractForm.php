<?php

namespace SprykerEngine\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractForm
{

    protected $form = null;

    protected $validation_rules = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @return $this
     */
    public function init()
    {
        $this->injectFrameworkDependencies();
        $this->buildFormFields();
        $data = $this->populateFormFields();
        $this->setData($data);
        return $this;
    }

    protected function injectFrameworkDependencies()
    {
        $this->request = $this->getLocator()->application()->pluginPimple()->getApplication()['request'];

        $this->formFactory = $this->getLocator()
            ->application()
            ->pluginPimple()
            ->getApplication()['form.factory'];

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
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            return $this->form->getData();
        }

        return false;
    }

    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

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

    public function addChoice($name, $options = array())
    {

        $this->add($name, 'choice', $options);
        return $this;
    }

    public function addHidden($name, $options = array())
    {
        $this->add($name, 'hidden', $options);
        return $this;
    }

    public function addSubmit($name = 'submit', $options = array())
    {
        $this->add($name, 'submit', $options);
        return $this;
    }
}
