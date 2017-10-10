<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api;

use DOMDocument;
use SimpleXMLElement as SimpleXMLElementSimpleXMLElement;

class SimpleXMLElement extends SimpleXMLElementSimpleXMLElement
{

    /**
     * @param string $sName
     * @param string $sValue
     *
     * @return $this
     */
    public function addCDataChild($sName, $sValue)
    {
        $dom = new DOMDocument();
        $cDataNode = $dom->appendChild($dom->createElement($sName));
        $cDataNode->appendChild($dom->createCDATASection($this->removeSpecialChars($sValue)));
        $oNodeOld = dom_import_simplexml($this);
        $oNodeTarget = $oNodeOld->ownerDocument->importNode($cDataNode, true);
        $oNodeOld->appendChild($oNodeTarget);
        return simplexml_import_dom($oNodeTarget, 'SimpleXMLElement');
    }

    /**
     * This method replaced all zoot signs
     *
     * @param string $str
     *
     * @return string
     */
    protected function removeSpecialChars($str)
    {
        $search = [
            "–" => "-",
            "´" => "'",
            "‹" => "<",
            "›" => ">",
            "‘" => "'",
            "’" => "'",
            "‚" => ",",
            "“" => '"',
            "”" => '"',
            "„" => '"',
            "‟" => '"',
            "•" => "-",
            "‒" => "-",
            "―" => "-",
            "—" => "-",
            "™" => "TM",
            "¼" => "1/4",
            "½" => "1/2",
            "¾" => "3/4",
        ];

        return str_replace(array_keys($search), array_values($search), $str);
    }

}
