<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PropelSchemaMergerInterface
{

    /**
     * @param array $schemaFiles
     *
     * @return string
     */
    public function merge(array $schemaFiles);

}
