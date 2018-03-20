<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment;

interface SubFormPluginInterface
{
    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormInterface
     */
    public function createSubForm();

//    /**
//     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\FormDataProviderInterface
//     */
//    public function createSubFormDataProvider();

    /**
     * @return string
     */
    public function getPropertyPath();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($dataTransfer);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getOptions($dataTransfer);
}
