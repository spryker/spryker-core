<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Zed\Propel\Business\Exception\EngineAwareCommandNotFoundException;

class PropelDatabaseEngineAwareCommandExecutor implements PropelDatabaseCommandExecutorInterface
{

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandCollectionInterface
     */
    protected $engineAwareCommandCollection;

    /**
     * @var string
     */
    protected $currentEngine;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandCollectionInterface $engineAwareCommandCollection
     * @param string $currentEngine
     */
    public function __construct(EngineAwareCommandCollectionInterface $engineAwareCommandCollection, $currentEngine)
    {
        $this->engineAwareCommandCollection = $engineAwareCommandCollection;
        $this->currentEngine = $currentEngine;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->getEngineAwareCommand()->__invoke();
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\EngineAwareCommandNotFoundException
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandInterface
     */
    protected function getEngineAwareCommand()
    {
        if (!$this->engineAwareCommandCollection->has($this->currentEngine)) {
            throw new EngineAwareCommandNotFoundException(
                sprintf('Can not find a Command for "%s" engine', $this->currentEngine)
            );
        }

        return $this->engineAwareCommandCollection->get($this->currentEngine);
    }

}
