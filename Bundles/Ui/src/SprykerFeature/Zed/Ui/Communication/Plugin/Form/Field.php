<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Form;

use SprykerFeature\Zed\Ui\Communication\Plugin\Form\Bean\FieldBeanTrait;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerFeature\Zed\Ui\Dependency\Plugin\AbstractFormPlugin;
use SprykerFeature\Zed\Ui\Library\Constraints\SerializeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Field extends AbstractFormPlugin
{

    const STATE_NEW = 'new';
    const STATE_VALIDATED = 'validated';
    const OUTPUT_TYPE_FIELD = 'field';
    const OUTPUT_LABEL = 'label';
    const OUTPUT_VALUE = 'value';
    const OUTPUT_REFRESH = 'refresh';
    const OUTPUT_ACCEPTS = 'accepts';
    const OUTPUT_MESSAGES = 'messages';
    const OUTPUT_EXTRA = 'extra';
    const OUTPUT_CONSTRAINTS = 'constraints';

    use FieldBeanTrait;

    /**
     * @var string
     */
    protected $validationState = self::STATE_NEW;

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @return mixed|null
     */
    protected function getAcceptedValue()
    {
        $value = $this->stateContainer->getActiveValue($this->getName());

        if ($this->acceptsValue($value)) {
            return $value;
        }

        return;
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        $messages = $this->getMessages();

        if ($this->stateContainer->receivedSubmitRequest()) {
            $messages = array_merge($messages, $this->getErrorMessages());
        }

        return [
            AbstractFormPlugin::OUTPUT_NAME => $this->getName(),
            AbstractFormPlugin::OUTPUT_TYPE => self::OUTPUT_TYPE_FIELD,
            self::OUTPUT_LABEL => $this->getLabel(),
            self::OUTPUT_VALUE => $this->getAcceptedValue(),
            self::OUTPUT_REFRESH => $this->getRefresh(),
            self::OUTPUT_ACCEPTS => $this->getKeyLessArray($this->getAccepts()),
            self::OUTPUT_MESSAGES => $this->getKeyLessArray($messages),
            self::OUTPUT_EXTRA => $this->getExtra(),
            self::OUTPUT_CONSTRAINTS => $this->serializeConstraints(),
        ];
    }

    /**
     * @param array $output
     *
     * @return array|void
     */
    public function extendOutput(array $output)
    {
        $output[AbstractForm::OUTPUT_FIELDS][] = $this->toArray();

        return $output;
    }

    /**
     * @param $mixed
     *
     * @return array
     */
    protected function getKeyLessArray($mixed)
    {
        if (!is_array($mixed)) {
            $mixed = [$mixed];
        } else {
            $mixed = array_values($mixed);
        }

        return $mixed;
    }

    /**
     * @param $mixed
     *
     * @return array
     */
    protected function getKeyLessArrayIfArray($mixed)
    {
        if (is_array($mixed)) {
            $mixed = array_values($mixed);
        }

        return $mixed;
    }

    /**
     * @return array
     */
    protected function serializeConstraints()
    {
        $serializedConstraints = [];
        foreach ($this->getConstraints() as $constraint) {
            if ($constraint instanceof SerializeInterface) {
                $serializedConstraint = $constraint->serialize();

                $serializedConstraints[$serializedConstraint['name']] = $serializedConstraint['options'];
            }
        }

        $serializedConstraints = $this->getEmptyStdClassIfEmpty($serializedConstraints);

        return $serializedConstraints;
    }

    /**
     * @param array $data
     *
     * @return array|\stdClass
     */
    protected function getEmptyStdClassIfEmpty(array $data)
    {
        if (count($data) === 0) {
            $data = new \stdClass();
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $errors = $this->getErrors();

        return !(bool) count($errors);
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function getErrors()
    {
        if (self::STATE_NEW === $this->validationState) {
            $this->errorMessages = $this->getDependencyContainer()->getValidator()->validateValue(
                $this->stateContainer->getRequestData(),
                $this->getConstraintCollection()
            );
            $this->validationState = self::STATE_VALIDATED;
        }

        return $this->errorMessages;
    }

    /**
     * @return array
     */
    protected function getErrorMessages()
    {
        $messages = [];
        foreach ($this->getErrors() as $error) {
            $pathComponents = explode(']', $error->getPropertyPath());

            $ref = &$messages;
            foreach ($pathComponents as $component) {
                $component = str_replace('[', '', $component);
                $component = str_replace(']', '', $component);

                if (!$component) {
                    continue;
                }

                $ref = &$ref[$component];
            }

            $ref[] = $error->getMessage();
        }

        return $messages;
    }

    /**
     * @return Assert\Collection
     */
    protected function getConstraintCollection()
    {
        $fields[$this->getName()] = $this->getConstraints();

        $options = [
            'allowExtraFields' => true,
            'fields' => $fields,
        ];

        return new Assert\Collection($options);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function acceptsValue($value)
    {
        $accepts = $this->getAccepts();

        $valueAccepted = in_array(
            $value,
            array_column($accepts, 'value')
        );

        if (!count($accepts)) {
            $valueAccepted = true;
        }

        return $valueAccepted;
    }

}
