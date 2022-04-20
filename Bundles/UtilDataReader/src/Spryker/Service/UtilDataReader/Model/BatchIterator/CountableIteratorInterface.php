<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator;

use Countable;
use Iterator;

/**
 * @extends \Iterator<mixed>
 */
interface CountableIteratorInterface extends Iterator, Countable
{
}
