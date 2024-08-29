<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\OrderMatrix\Business\Writer;

interface OrderMatrixWriterInterface
{
    /**
     * @return void
     */
    public function writeOrderMatrix(): void;
}
