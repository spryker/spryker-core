<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
