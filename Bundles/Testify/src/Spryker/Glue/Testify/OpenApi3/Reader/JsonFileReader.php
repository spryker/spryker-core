<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @inheritdoc
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
