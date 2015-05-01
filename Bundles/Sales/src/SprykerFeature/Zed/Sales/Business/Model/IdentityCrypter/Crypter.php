<?php

namespace SprykerFeature\Zed\Sales\Business\Model\IdentityCrypter;

class Crypter extends IdentityCrypter
{

    const MAX_INCREMENT_LENGTH = 20;
    const DEVELOPMENT_DELIMITER = '-';

    /**
     * @var int
     */
    protected $numberOfDigits;

    /**
     * @var string
     */
    protected $incrementPrefix;

    /**
     * @param array $keys
     * @param int $numberOfDigits
     * @param string $incrementPrefix
     */
    public function __construct(array $keys, $numberOfDigits, $incrementPrefix = '')
    {
        parent::__construct($keys);

        $this->numberOfDigits = $numberOfDigits;
        $this->incrementPrefix = $incrementPrefix;
    }

    /**
     * @param int $number
     * @param bool $appendDevelopmentModeCharacters
     *
     * @return string
     */
    public function encrypt($number, $appendDevelopmentModeCharacters = true)
    {
        $increment = parent::encrypt($number);

        $digitsWithoutPrefix = $this->numberOfDigits - strlen($this->incrementPrefix);
        $increment = sprintf($this->incrementPrefix . '%0' . $digitsWithoutPrefix . 's', $increment);

        if (\SprykerFeature_Shared_Library_Environment::isNotProduction() && $appendDevelopmentModeCharacters) {
            $increment = $this->appendDevelopmentDigits($increment);
        }

        return $increment;
    }

    /**
     * @param string $value
     * @throws \Exception
     */
    public function decrypt($value)
    {
         throw new \Exception('To be implemented!');
    }

    /**
     * @param int $increment
     *
     * @return string
     */
    protected function appendDevelopmentDigits($increment)
    {
        if (!$this->canAddAdditionalCharacter($increment)) {
            return $increment;
        }

        $charactersToAdd = (self::MAX_INCREMENT_LENGTH - strlen(self::DEVELOPMENT_DELIMITER)) - strlen($increment);
        $appendString = substr(time(), (-1) * $charactersToAdd);

        return $increment . self::DEVELOPMENT_DELIMITER . $appendString;
    }

    /**
     * @param $increment
     *
     * @return bool
     */
    private function canAddAdditionalCharacter($increment)
    {
        return self::MAX_INCREMENT_LENGTH <= strlen($increment);
    }

}
