<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Cldr;

use RuntimeException;

class JsonFileCldrDataProvider implements CldrDataProviderInterface
{
    /**
     * @var string
     */
    protected $cldrFilePath;

    /**
     * @param string $cldrFilePath
     */
    public function __construct(string $cldrFilePath)
    {
        $this->cldrFilePath = $cldrFilePath;
    }

    /**
     * @throws \RuntimeException
     *
     * @return array<mixed>
     */
    public function getCldrData(): array
    {
        $rawFileInput = file_get_contents(
            $this->cldrFilePath,
        );
        if ($rawFileInput === false) {
            throw new RuntimeException('Invalid content for cldr file.');
        }

        return json_decode($rawFileInput, true);
    }
}
