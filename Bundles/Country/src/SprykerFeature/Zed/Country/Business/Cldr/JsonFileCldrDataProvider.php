<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business\Cldr;

class JsonFileCldrDataProvider implements CldrDataProviderInterface
{

    /**
     * @var string
     */
    protected $cldrFilePath;

    /**
     * @param string $cldrFilePath
     */
    public function __construct($cldrFilePath)
    {
        $this->cldrFilePath = $cldrFilePath;
    }

    public function getCldrData()
    {
        $rawFileInput = file_get_contents(
            $this->cldrFilePath
        );

        return json_decode($rawFileInput, true);
    }

}
