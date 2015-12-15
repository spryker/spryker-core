<?php

namespace Spryker\Zed\Gui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\Validator\Constraint;

/**
 * @deprecated use AbstractFormType instead
 * this trait will not be necessary for AbstractFormType
 */
trait FormBuilderTrait
{

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param string|Constraint $type
     * @param array $options
     *
     * @return self
     */
    public function add($name, $type, $options = [])
    {
        $this->form->add($name, $type, $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addText($name, $options = [])
    {
        $this->add($name, 'text', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addTextarea($name, $options = [])
    {
        $this->add($name, 'textarea', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addEmail($name, $options = [])
    {
        $this->add($name, 'email', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addInteger($name, $options = [])
    {
        $this->add($name, 'integer', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addMoney($name, $options = [])
    {
        $this->add($name, 'money', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addNumber($name, $options = [])
    {
        $this->add($name, 'number', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addPassword($name, $options = [])
    {
        $this->add($name, 'password', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addPercent($name, $options = [])
    {
        $this->add($name, 'percent', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addSearch($name, $options = [])
    {
        $this->add($name, 'search', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addUrl($name, $options = [])
    {
        $this->add($name, 'url', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addChoice($name, $options = [])
    {
        $this->add($name, 'choice', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addEntity($name, $options = [])
    {
        $this->add($name, 'entity', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addCountry($name, $options = [])
    {
        $this->add($name, 'country', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addLanguage($name, $options = [])
    {
        $this->add($name, 'language', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addLocale($name, $options = [])
    {
        $this->add($name, 'locale', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addTimezone($name, $options = [])
    {
        $this->add($name, 'timezone', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addCurrency($name, $options = [])
    {
        $this->add($name, 'currency', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addDate($name, $options = [])
    {
        $this->add($name, 'date', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addDatetime($name, $options = [])
    {
        $this->add($name, 'datetime', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addTime($name, $options = [])
    {
        $this->add($name, 'time', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addBirthday($name, $options = [])
    {
        $this->add($name, 'birthday', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addCheckbox($name, $options = [])
    {
        $this->add($name, 'checkbox', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addFile($name, $options = [])
    {
        $this->add($name, 'file', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addRadio($name, $options = [])
    {
        $this->add($name, 'radio', $options);

        return $this;
    }

    /* == Field Groups == */

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addCollection($name, $options = [])
    {
        $this->add($name, 'collection', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addRepeated($name, $options = [])
    {
        $this->add($name, 'repeated', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addHidden($name, $options = [])
    {
        $this->add($name, 'hidden', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addButton($name, $options = [])
    {
        $this->add($name, 'button', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addReset($name, $options = [])
    {
        $this->add($name, 'reset', $options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return self
     *
     * @deprecated Forms should not have submit buttons http://symfony.com/doc/current/best_practices/forms.html#form-button-configuration
     */
    public function addSubmit($name = 'submit', $options = [])
    {
        $this->add($name, 'submit', $options);

        return $this;
    }

    /* == Base fields == */

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addForm($name, $options = [])
    {
        $this->add($name, 'form', $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addSelect($name, $options = [])
    {
        $this->add($name, new SelectType(), $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * Select2 default combobox styling, see https://select2.github.io/examples.html
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addSelect2ComboBox($name, $options = [])
    {
        $this->add($name, new Select2ComboBoxType(), $options);

        return $this;
    }

    /**
     * @deprecated use AbstractFormType instead
     *
     * @param string $name
     * @param array $options
     *
     * @return self
     */
    public function addAutosuggest($name, $options = [])
    {
        $this->add($name, new AutosuggestType(), $options);

        return $this;
    }

}
