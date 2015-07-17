<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import\InputTypeDetector;

use SprykerFeature\Zed\Library\Import\Exception;
use SprykerFeature\Zed\Library\Import\Input;
use SprykerFeature\Zed\Library\Import\InputTypeDetectorInterface;

/**
 * Returns a fixed type regardless of the Input object
 */
class FixedTypeDetector implements InputTypeDetectorInterface
{

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param Input $input
     *
     * @throws Exception\ImportTypeNotDetectedException
     *
     * @return string The type
     */
    public function detect(Input $input)
    {
        return $this->type;
    }

}
