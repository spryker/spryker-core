<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business;

use Spryker\Zed\CustomerNote\Business\Model\NoteWriter;
use Spryker\Zed\CustomerNote\Business\Model\NoteWriterInterface;
use Spryker\Zed\CustomerNote\CustomerNoteDependencyProvider;
use Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CustomerNoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerNote\Business\Model\NoteWriterInterface
     */
    public function createNoteWriter(): NoteWriterInterface
    {
        return new NoteWriter(
            $this->getUserFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface
     */
    protected function getUserFacade(): CustomerNoteToUserFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteDependencyProvider::FACADE_USER);
    }
}
