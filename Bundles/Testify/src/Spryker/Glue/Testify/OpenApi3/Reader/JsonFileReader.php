<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reader;

use Spryker\Glue\Testify\OpenApi3\Exception\ParseException;
use Spryker\Glue\Testify\OpenApi3\ReaderInterface;

class JsonFileReader implements ReaderInterface
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @inheritDoc
     *
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     */
    public function read()
    {
        $fileContent = file_get_contents($this->fileName);

        if ($fileContent === false) {
            throw new ParseException(sprintf('File "%s" could not be read', $this->fileName));
        }

        $json = json_decode($fileContent);

        if (json_last_error()) {
            throw new ParseException(json_last_error_msg());
        }

        return $json;
    }
}
