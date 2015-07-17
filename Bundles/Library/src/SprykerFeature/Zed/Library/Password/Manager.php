<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class SprykerFeature_Zed_Library_Password_Manager
{

    const MAX_PASSWORD_LENGTH = 4096;

    /**
     * @var string
     */
    private $algorithm = PASSWORD_DEFAULT;

    /**
     * @var array
     */
    private $algorithmOptions = [];

    /**
     * @param string $algorithm
     * @param array $algorithmOptions
     */
    public function __construct($algorithm, $algorithmOptions = [])
    {
        $this->algorithm = $algorithm;
        $this->algorithmOptions = $algorithmOptions;
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algorithm, $this->algorithmOptions);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function verify($password, $hash)
    {
        if (!$this->isPasswordTooLong($password) && password_verify($password, $hash)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $password
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function hash($password)
    {
        if ($this->isPasswordTooLong($password)) {
            throw new \RuntimeException('Password is to long.');
        }

        $hash = password_hash($password, $this->algorithm, $this->algorithmOptions);
        if ($hash === false) {
            throw new \RuntimeException(sprintf(
                "Something went wrong while hashing the password.\n[Algorithm] %s\n[Options] %s",
                $this->algorithm,
                print_r($this->algorithmOptions, true)
            ));
        }

        return $hash;
    }

    /**
     * Checks if the password is too long.
     *
     * @param string $password
     *
     * @return Boolean true if the password is too long, false otherwise
     */
    protected function isPasswordTooLong($password)
    {
        return strlen($password) > self::MAX_PASSWORD_LENGTH;
    }

}
