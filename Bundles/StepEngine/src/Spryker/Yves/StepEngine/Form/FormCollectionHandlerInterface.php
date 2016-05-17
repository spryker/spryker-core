<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Form;

use Spryker\Shared\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;

interface FormCollectionHandlerInterface
{

    /**
     * @return \Symfony\Component\Form\FormInterface[]
     */
    public function getForms();

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function hasSubmittedForm(Request $request);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Form\FormInterface|null
     */
    public function handleRequest(Request $request);

    /**
     * @return void
     */
    public function provideDefaultFormData();

    /**
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function getDataClass();

}
