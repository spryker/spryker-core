<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

interface LabelReaderInterface
{

    /**
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getByIdProductLabel($idProductLabel);

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAll();

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllByIdProductAbstract($idProductAbstract);

}
