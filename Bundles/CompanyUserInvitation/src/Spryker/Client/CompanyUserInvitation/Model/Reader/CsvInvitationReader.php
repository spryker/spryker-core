<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Model\Reader;

use Iterator;
use League\Csv\Reader;

class CsvInvitationReader implements InvitationReaderInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return \Iterator
     */
    public function getInvitations(): Iterator
    {
        $csv = Reader::createFromPath($this->filePath, 'r');

        return $csv->fetchAssoc();
    }
}
