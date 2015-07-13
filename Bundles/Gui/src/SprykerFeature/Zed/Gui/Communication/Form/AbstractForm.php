<?php

namespace SprykerFeature\Zed\Gui\Communication\Form;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Tests\Domain\AuditLoggerTest;
use SprykerFeature\Zed\Gui\Communication\Form\Type\SelectType;
use SprykerFeature\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;

/**
 * Class AbstractForm
 * @package SprykerFeature\Zed\Gui\Communication\Form
 */
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
     * Prepares form
     *
     * @return $this
     */
    abstract protected function buildFormFields();

    /**
     * Set the values for fields
     *
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
     * @param array $data
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
     * @return FormErrorIterator
     */
    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    /**
     * @param string $name
     * @param string|Constraint $type
     * @param array $options
     *
     * @return $this
     */
    public function add($name, $type, $options = array())
    {
        return $this->form->add($name, $type, $options);
    }

    /**
     * @param string $name 
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
     * @param string $name
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
     * @param string $name
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
     * @param string $name
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
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addMoney($name, $options = array())
    {
        $this->add($name, 'money', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addNumber($name, $options = array())
    {
        $this->add($name, 'number', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addPassword($name, $options = array())
    {
        $this->add($name, 'password', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addPercent($name, $options = array())
    {
        $this->add($name, 'percent', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addSearch($name, $options = array())
    {
        $this->add($name, 'search', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addUrl($name, $options = array())
    {
        $this->add($name, 'url', $options);

        return $this;
    }

    /**
     * @param string $name
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
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addEntity($name, $options = array())
    {
        $this->add($name, 'entity', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCountry($name, $options = array())
    {
        $this->add($name, 'country', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addLanguage($name, $options = array())
    {
        $this->add($name, 'language', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addLocale($name, $options = array())
    {
        $this->add($name, 'locale', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addTimezone($name, $options = array())
    {
        $this->add($name, 'timezone', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCurrency($name, $options = array())
    {
        $this->add($name, 'currency', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addDate($name, $options = array())
    {
        $this->add($name, 'date', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addDatetime($name, $options = array())
    {
        $this->add($name, 'datetime', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addTime($name, $options = array())
    {
        $this->add($name, 'time', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addBirthday($name, $options = array())
    {
        $this->add($name, 'birthday', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCheckbox($name, $options = array())
    {
        $this->add($name, 'checkbox', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addFile($name, $options = array())
    {
        $this->add($name, 'file', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRadio($name, $options = array())
    {
        $this->add($name, 'radio', $options);

        return $this;
    }

    /* == Field Groups == */

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addCollection($name, $options = array())
    {
        $this->add($name, 'collection', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRepeated($name, $options = array())
    {
        $this->add($name, 'repeated', $options);

        return $this;
    }

    /**
     * @param string $name
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
    public function addButton($name, $options = array())
    {
        $this->add($name, 'button', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addReset($name, $options = array())
    {
        $this->add($name, 'reset', $options);

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

    /* == Base fields == */

    /**
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addForm($name, $options = array())
    {
        $this->add($name, 'form', $options);

        return $this;
    }

    /**
     * @param string $name
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
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addAutosuggest($name, $options = array())
    {
        $this->add($name, new AutosuggestType(), $options);

        return $this;
    }
}
