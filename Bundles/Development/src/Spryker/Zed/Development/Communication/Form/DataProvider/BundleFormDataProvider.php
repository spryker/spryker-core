<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Spryker\Zed\Development\Communication\Form\BundlesFormType;
use Symfony\Component\HttpFoundation\Request;

class BundleFormDataProvider
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    protected $bundleDependencyCollectionTransfer;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $bundleDependencyCollectionTransfer
     */
    public function __construct(Request $request, DependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $this->request = $request;
        $this->bundleDependencyCollectionTransfer = $bundleDependencyCollectionTransfer;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $excludedBundles = [];
        if ($this->request->request->has(BundlesFormType::FORM_TYPE_NAME)) {
            /** @var array $formData */
            $formData = $this->request->request->get(BundlesFormType::FORM_TYPE_NAME);
            if (isset($formData[BundlesFormType::EXCLUDED_BUNDLES])) {
                $excludedBundles = $formData[BundlesFormType::EXCLUDED_BUNDLES];
            }
        }

        return [BundlesFormType::BUNDLE_NAME_CHOICES => $excludedBundles];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            BundlesFormType::BUNDLE_NAME_CHOICES => $this->getBundleChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getBundleChoices()
    {
        $dependencies = $this->getBundleNames($this->bundleDependencyCollectionTransfer);

        return array_combine($dependencies, $dependencies);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleNames(DependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyModules() as $dependencyBundleTransfer) {
            $hasDependencyInSource = false;

            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest() && !$dependencyTransfer->getIsOptional()) {
                    $hasDependencyInSource = true;
                }
            }

            if ($hasDependencyInSource) {
                $bundleNames[] = $dependencyBundleTransfer->getModule();
            }
        }

        return $bundleNames;
    }
}
