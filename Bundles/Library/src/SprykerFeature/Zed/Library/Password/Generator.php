<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class SprykerFeature_Zed_Library_Password_Generator
{

    protected $allowedChars = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ1234567890!?.';

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomPassword($length = 10)
    {
        $pass = '';
        $alphaLength = strlen($this->allowedChars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass .= $this->allowedChars[$n];
        }

        return $pass;
    }

}
