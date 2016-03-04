<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model;

/**
 * DO NOT USE THIS CLASS in general. It is meant to be used in the bootstrapping when it would be inconvenient to
 * determine every time whether to use the Yves or Zed Journal.
 */

class SharedEventJournal extends AbstractEventJournal
{
}
