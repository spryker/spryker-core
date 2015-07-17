<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import\Exception;

class SourceNotValidException extends \RuntimeException implements ImportExceptionInterface
{

    /**
     * @var int
     */
    private $rowNumber;

    /**
     * @param int $rowNumber
     * @param string $message
     */
    public function __construct($rowNumber = 0, $message = '')
    {
        parent::__construct($message);
        $this->rowNumber = $rowNumber;
    }

    /**
     * @return int
     */
    public function getRowNumber()
    {
        return $this->rowNumber;
    }

    /**
     * @param int $rowNumber
     *
     * @return $this
     */
    public function setRowNumber($rowNumber)
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

}
