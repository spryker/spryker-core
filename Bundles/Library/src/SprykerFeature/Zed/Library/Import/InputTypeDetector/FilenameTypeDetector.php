<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import\InputTypeDetector;

use SprykerFeature\Zed\Library\Import\Exception;
use SprykerFeature\Zed\Library\Import\Input;
use SprykerFeature\Zed\Library\Import\InputTypeDetectorInterface;

/**
 * Returns a one of the validTypes, that matches the regex
 * in the filenameRegexes
 */
class FilenameTypeDetector implements InputTypeDetectorInterface
{

    const REGEX_DELIMITER = '#';

    /**
     * @var array
     */
    private $filenameRegexes;

    /**
     * @param array $filenameRegexes (type => regex)
     */
    public function __construct(array $filenameRegexes)
    {
        $this->filenameRegexes = $filenameRegexes;
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
        $source = $input->getSource();
        foreach ($this->filenameRegexes as $type => $regexp) {
            $regexp = $this->prepareRegexp($regexp);
            if (preg_match($regexp, $source)) {
                return $type;
            }
        }
        throw new Exception\ImportTypeNotDetectedException();
    }

    /**
     * @param string $regexp
     *
     * @return string
     */
    protected function prepareRegexp($regexp)
    {
        $regexp = addcslashes($regexp, self::REGEX_DELIMITER);
        $regexp = self::REGEX_DELIMITER . $regexp . self::REGEX_DELIMITER . 'i';

        return $regexp;
    }

}
