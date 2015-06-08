<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PropelSchemaWriterInterface
{

    /**
     * @param string $fileName
     * @param string $content
     */
    public function write($fileName, $content);

}
