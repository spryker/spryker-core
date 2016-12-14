<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

class BundleMethodGenerator extends AbstractGenerator
{

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $bundleTransferCollection
     *
     * @return string
     */
    public function generate(array $bundleTransferCollection)
    {
        $templateVariables = [
            'bundleTransferCollection' => $bundleTransferCollection,
            'namespace' => $this->getNamespace(),
        ];

        return $this->twig->render($this->getTemplateName(), $templateVariables);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BundleAutoCompletion';
    }

}
