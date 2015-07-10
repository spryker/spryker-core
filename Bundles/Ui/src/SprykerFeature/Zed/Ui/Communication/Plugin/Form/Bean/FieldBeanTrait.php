<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Form\Bean;

trait FieldBeanTrait
{

    /**
     * @var string
     */
    protected $name = null;

    protected $nameHook = null;

    /**
     * @var null
     */
    protected $value = null;

    protected $valueHook = null;

    /**
     * @var string
     */
    protected $label = null;

    protected $labelHook = null;

    /**
     * @var array
     */
    protected $constraints = [];

    protected $constraintsHook = null;

    /**
     * @var array
     */
    protected $accepts = [];

    protected $acceptsHook = null;

    /**
     * @var bool
     */
    protected $refresh = false;

    protected $refreshHook = null;

    /**
     * @var array
     */
    protected $messages = [];

    protected $messagesHook = null;

    /**
     * @var array
     */
    protected $extra = [];

    protected $extraHook = null;

    /**
     * @return array
     */
    public function getAccepts()
    {
        if ($this->acceptsHook) {
            return $this->acceptsHook->__invoke($this->accepts);
        }

        return $this->accepts;
    }

    /**
     * @param $accepts
     *
     * @return $this
     *
     * The spy-form frontend elements expect an array like this:
     * [
     *      [
     *          'value' => 1,
     *          'label' => 'Foo',
     *      ],
     *      [
     *          'value' => 2,
     *          'label' => 'Bar',
     *      ]
     * ]
     */
    public function setAccepts($accepts)
    {
        $this->accepts = $accepts;

        return $this;
    }

    /**
     * @param $acceptsHook
     *
     * @return $this
     */
    public function setAcceptsHook($acceptsHook)
    {
        $this->acceptsHook = $acceptsHook;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if ($this->labelHook) {
            return $this->labelHook->__invoke($this->label);
        }

        return $this->label;
    }

    /**
     * @param $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param $labelHook
     *
     * @return $this
     */
    public function setLabelHook($labelHook)
    {
        $this->labelHook = $labelHook;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        if ($this->messagesHook) {
            return $this->messagesHook->__invoke($this->messages);
        }

        return $this->messages;
    }

    /**
     * @param $messages
     *
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param $messagesHook
     *
     * @return $this
     */
    public function setMessagesHook($messagesHook)
    {
        $this->messagesHook = $messagesHook;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        if ($this->extraHook) {
            return $this->extraHook->__invoke($this->extra);
        }

        return $this->extra;
    }

    /**
     * @param $extra
     *
     * @return $this
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param $extraHook
     *
     * @return $this
     */
    public function setExtraHook($extraHook)
    {
        $this->extraHook = $extraHook;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->nameHook) {
            return $this->nameHook->__invoke($this->name);
        }

        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $nameHook
     *
     * @return $this
     */
    public function setNameHook($nameHook)
    {
        $this->nameHook = $nameHook;

        return $this;
    }

    /**
     */
    public function getValue()
    {
        if ($this->valueHook) {
            return $this->valueHook->__invoke($this->value);
        }

        return $this->value;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $valueHook
     *
     * @return $this
     */
    public function setValueHook($valueHook)
    {
        $this->valueHook = $valueHook;

        return $this;
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        if ($this->constraintsHook) {
            return $this->constraintsHook->__invoke($this->constraints);
        }

        return $this->constraints;
    }

    /**
     * @param $constraints
     *
     * @return $this
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = $constraints;

        return $this;
    }

    public function setConstraintsHook($constraintsHook)
    {
        $this->constraintsHook = $constraintsHook;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRefresh()
    {
        if ($this->refreshHook) {
            return $this->refreshHook->__invoke($this->refresh);
        }

        return $this->refresh;
    }

    /**
     * @param $refresh
     *
     * @return $this
     */
    public function setRefresh($refresh)
    {
        $this->refresh = $refresh;

        return $this;
    }

    public function setRefreshHook($refreshHook)
    {
        $this->refreshHook = $refreshHook;

        return $this;
    }

}
