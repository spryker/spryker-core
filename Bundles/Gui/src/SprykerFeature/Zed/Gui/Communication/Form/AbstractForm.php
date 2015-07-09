<?php

namespace SprykerFeature\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Tests\Domain\AuditLoggerTest;
use SprykerFeature\Zed\Gui\Communication\Form\Type\SelectType;
use Symfony\Component\Form\Form;

abstract class AbstractForm
{
    /**
     * @var Form
     */
    protected $form = null;

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
    abstract protected function buildFormFields();

    /**
     * @return $this
     */
    abstract protected function populateFormFields();

    /**
     * @return $this
     */
    protected function injectDependencies()
    {
        $app = $this->getLocator()
            ->application()
            ->pluginPimple()
            ->getApplication();

        $this->request = $app['request'];
        $this->formFactory = $app['form.factory'];

        $this->form = $this->formFactory->create();

        return $this;
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
     */
    public function init()
    {
        $this->injectDependencies()
                ->buildFormFields();

        $data = $this->populateFormFields();
        $this->setData($data);

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->form->setData($data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function createView()
    {
        return $this->form->createView();
    }

    /**
     * @return $this
     */
    public function handleRequest()
    {
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
        return $this->form->getData();
    }

    /**
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @param $name
     * @param $type
     * @param array $options
     *
     * @return $this
     */
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
    public function addText($name, $options = array())
    {
        $this->add($name, 'text', $options);
        return $this;
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
    public function addSelect($name, $options = array())
    {
        $this->add($name, new SelectType(), $options);
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

    // TODO add all internal
}
