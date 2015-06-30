<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Exception\SchemaMergeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

interface PropelSchemaMergerInterface
{

    /**
     * @param array $schemaFiles
     *
     * @return string
     */
    public function merge(array $schemaFiles);

}
