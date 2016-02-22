<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
