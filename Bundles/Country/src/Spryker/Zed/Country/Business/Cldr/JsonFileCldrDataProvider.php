<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Business\Cldr;

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

    /**
     * @return mixed
     */
    public function getCldrData()
    {
        $rawFileInput = file_get_contents(
            $this->cldrFilePath
        );

        return json_decode($rawFileInput, true);
    }

}
