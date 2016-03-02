<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model;

interface EventInterface
{

    /**
     * @param string $name
     * @param array|string $data
     *
     * @return void
     */
    public function setField($name, $data);

    /**
     * @param array $fields
     *
     * @return void
     */
    public function setFields(array $fields);

    /**
     * @return array
     */
    public function getFields();

}
