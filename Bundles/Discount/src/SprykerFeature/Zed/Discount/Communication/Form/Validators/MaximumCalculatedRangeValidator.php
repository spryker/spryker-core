<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form\Validators;

class MaximumCalculatedRangeValidator
{

    /**
     * @var int
     */
    protected $charactersCount;

    /**
     * @param int $charactersCount
     */
    public function __construct($charactersCount)
    {
        $this->charactersCount = $charactersCount;
    }

    /**
     * @param int $n
     *
     * @return int
     */
    public function factorial($n)
    {
        $factor = 1;

        if ($n < 2) {
            return $factor;
        }

        for ($i=1; $i<=$n; $i++) {
            $factor *= $i;
        }

        return $factor;
    }

    /**
     * @param int $numberOfCombinations
     *
     * @return int
     */
    public function getPossibleCodeCombinationsCount($numberOfCombinations)
    {
        if ($numberOfCombinations > $this->charactersCount || $this->charactersCount < 1) {
            return 0;
        }

        $difference = $this->charactersCount - $numberOfCombinations;
        $numitor = $this->factorial($numberOfCombinations) * $this->factorial($difference);
        $possibleCodeCombinations = $this->factorial($this->charactersCount) / $numitor;

        return (int) $possibleCodeCombinations;
    }

}
