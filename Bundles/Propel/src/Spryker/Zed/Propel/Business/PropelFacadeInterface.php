<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business;

interface PropelFacadeInterface
{

    /**
     * @return void
     */
    public function cleanPropelSchemaDirectory();

    /**
     * @return void
     */
    public function copySchemaFilesToTargetDirectory();

    /**
     * @return void
     */
    public function adjustPropelSchemaFilesForPostgresql();

    /**
     * @return void
     */
    public function adjustPostgresqlFunctions();

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

}
