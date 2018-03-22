<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Exception\DatabaseCreatorNotFoundException;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollectionInterface;

/**
 * @deprecated Use `Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine` instead.
 */
class PropelDatabase implements PropelDatabaseInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollectionInterface
     */
    protected $databaseCreatorCollection;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollectionInterface $databaseCreatorCollection
     */
    public function __construct(DatabaseCreatorCollectionInterface $databaseCreatorCollection)
    {
        $this->databaseCreatorCollection = $databaseCreatorCollection;
    }

    /**
     * @return void
     */
    public function createDatabaseIfNotExists()
    {
        $this->getDatabaseCreator()->createIfNotExists();
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\DatabaseCreatorNotFoundException
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface
     */
    protected function getDatabaseCreator()
    {
        $engine = Config::get(PropelConstants::ZED_DB_ENGINE);

        if (!$this->databaseCreatorCollection->has($engine)) {
            throw new DatabaseCreatorNotFoundException(
                sprintf('Can not find a DatabaseCreator for "%s" engine', $engine)
            );
        }

        return $this->databaseCreatorCollection->get($engine);
    }
}
