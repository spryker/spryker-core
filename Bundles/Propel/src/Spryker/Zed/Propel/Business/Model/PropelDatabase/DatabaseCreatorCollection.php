<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

class DatabaseCreatorCollection implements DatabaseCreatorCollectionInterface
{

    /**
     * @var array
     */
    protected $databaseCreator = [];

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface $databaseCreator
     *
     * @return $this
     */
    public function add(DatabaseCreatorInterface $databaseCreator)
    {
        $this->databaseCreator[$databaseCreator->getEngine()] = $databaseCreator;

        return $this;
    }

    /**
     * @param string $engine
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface
     */
    public function get($engine)
    {
        return $this->databaseCreator[$engine];
    }

    /**
     * @param string $engine
     *
     * @return bool
     */
    public function has($engine)
    {
        return array_key_exists($engine, $this->databaseCreator);
    }

}
