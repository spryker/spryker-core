<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

interface PropelSchemaWriterInterface
{

    /**
     * @param string $fileName
     * @param string $content
     */
    public function write($fileName, $content);

}
