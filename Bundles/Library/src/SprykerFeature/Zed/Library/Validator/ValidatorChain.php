<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Validator;

use SprykerEngine\Shared\Transfer\AbstractTransfer;

/**
 * Class ValidatorChain
 */
class ValidatorChain
{

    /**
     * If you pass this as $type param to getValidatorChain you will
     * get back the configured validators for the create validation
     */
    const CREATE = 'validate create';

    /**
     * If you pass this as $type param to getValidatorChain you will
     * get back the configured validators for the update validation
     */
    const UPDATE = 'validate update';

    /**
     * Under this key your validators will be stored in the chain,
     * you don´t have to take care about it nor use it
     */
    const DEFAULT_VALIDATION = 'default validation';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $validatorChain = [
        self::CREATE => [],
        self::UPDATE => [],
        self::DEFAULT_VALIDATION => [],
    ];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var bool
     */
    protected $hasErrors = false;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
    }

    /**
     * You can pass one of the defined validation types or null
     * If you pass null it will return the default validation chain
     *
     * @param null|string $validationType
     *
     * @return array
     */
    public function getValidatorChain($validationType = self::DEFAULT_VALIDATION)
    {
        $this->validateValidationType($validationType);

        return $this->validatorChain[$validationType];
    }

    /**
     * Use this method to add a validator to a validationType and fieldName
     * $fieldName is the field which should be validated e.g. first_name
     *
     * @param \Zend_Validate_Interface $validator
     * @param $fieldName
     * @param string $validationType
     *
     * @return $this
     */
    public function addValidator(\Zend_Validate_Interface $validator, $fieldName, $validationType = self::DEFAULT_VALIDATION)
    {
        $this->validateValidationType($validationType);
        $this->validatorChain[$validationType][$fieldName][] = $validator;

        return $this;
    }

    /**
     * @param array $validators
     * @param $fieldName
     * @param string $validationType
     *
     * @return $this
     */
    public function addValidators(array $validators, $fieldName, $validationType = self::DEFAULT_VALIDATION)
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator, $fieldName, $validationType);
        }

        return $this;
    }

    /**
     * @param string $validationType
     *
     * @throws \Exception
     */
    protected function validateValidationType($validationType)
    {
        if (!array_key_exists($validationType, $this->validatorChain)) {
            throw new \Exception('Given validationType "' . $validationType . '" is not allowed!');
        }
    }

    /**
     * If you pass an AbstractTransfer it will be converted to an array
     *
     * You also can pass one of the defined validation types or null
     * If you pass null validate will use the default validation chain
     *
     * @param array|AbstractTransfer $data
     * @param string $validationType
     *
     * @return bool
     */
    public function isValid($data, $validationType = self::DEFAULT_VALIDATION)
    {
        if ($data instanceof AbstractTransfer) {
            $data = $data->toArray();
        }
        $this->data = $data;
        $validatorChain = $this->getValidatorChain($validationType);
        foreach ($validatorChain as $fieldName => $validators) {
            $this->validateField($validators, $fieldName);
        }

        return !$this->hasErrors;
    }

    /**
     * @param \Zend_Validate_Interface[] $validators
     * @param string $fieldName
     */
    protected function validateField(array $validators, $fieldName)
    {
        foreach ($validators as $validator) {
            $isValid = $validator->isValid($this->data[$fieldName], $this->data);
            if (!$isValid) {
                $this->hasErrors = true;
                if (!isset($this->messages[$fieldName])) {
                    $this->messages[$fieldName] = [];
                }
                $this->messages[$fieldName] = array_merge(
                    $this->messages[$fieldName],
                    $validator->getMessages()
                );
            }
        }
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function hasCreateValidator()
    {
        return $this->hasValidationType(self::CREATE);
    }

    /**
     * @return bool
     */
    public function hasUpdateValidator()
    {
        return $this->hasValidationType(self::UPDATE);
    }

    /**
     * @param $validationType
     *
     * @return bool
     */
    protected function hasValidationType($validationType)
    {
        $validatorChain = $this->getValidatorChain($validationType);

        return !empty($validatorChain);
    }

}
