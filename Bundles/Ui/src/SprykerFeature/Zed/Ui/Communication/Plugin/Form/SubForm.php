<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerFeature\Zed\Ui\Dependency\Plugin\AbstractFormPlugin;

class SubForm extends AbstractFormPlugin
{

    const OUTPUT_TYPE_GROUP = 'group';

    /**
     * @var AbstractForm
     */
    protected $form;

    /**
     * string
     */
    protected $name;

    /**
     * @param array $output
     *
     * @return mixed
     */
    public function extendOutput(array $output)
    {
        $output[AbstractForm::OUTPUT_FIELDS][] = $this->toArray();

        return $output;
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        return [
            AbstractFormPlugin::OUTPUT_NAME => $this->getName(),
            AbstractFormPlugin::OUTPUT_TYPE => self::OUTPUT_TYPE_GROUP,
            AbstractForm::OUTPUT_FIELDS => $this->form->toArray()[AbstractForm::OUTPUT_FIELDS],
        ];
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->form->isValid();
    }

    /**
     * @return string
     */
    public function getName()
    {
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
     * @return AbstractForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param AbstractForm $form
     *
     * @return $this
     */
    public function setForm(AbstractForm $form)
    {
        $this->form = $form;

        $form->init();

        return $this;
    }

}
