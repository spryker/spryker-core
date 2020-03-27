<?php

namespace Spryker\Zed\Propel\Business\ConfigReader;

interface PropelConfigReaderInterface
{
    /**
     * @return string
     */
    public function getSchemaDirectory(): string;
}
