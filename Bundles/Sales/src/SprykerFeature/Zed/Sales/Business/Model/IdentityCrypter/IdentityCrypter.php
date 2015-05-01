<?php

namespace SprykerFeature\Zed\Sales\Business\Model\IdentityCrypter;

class IdentityCrypter
{

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @param array $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = array_unique($keys);
    }

    /**
     * @param int $number
     *
     * @return string
     */
    public function encrypt($number)
    {
        $encrypted = '';
        $keyCount = count($this->keys);

        while (floor($number) != 0) {
            $tmp = $number % $keyCount;
            $encrypted .= $this->keys[$tmp];
            $number = floor(($number - $tmp) / $keyCount);
        }

        return $encrypted;
    }

    /**
     * @param string $value
     * @return int
     */
    public function decrypt($value)
    {
        $decrypted = 0;
        $keyCount = count($this->keys);
        $multiplier = 1;

        foreach (str_split($value) as $character) {
            $key = array_search($character, $this->keys);
            $decrypted += $key * $multiplier;
            $multiplier *= $keyCount;
        }

        return $decrypted;
    }

}
