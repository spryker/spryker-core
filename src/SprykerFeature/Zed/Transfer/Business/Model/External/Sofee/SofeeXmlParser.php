<?php 
/*
  +----------------------------------------------------------------------+
  | Sofee Framework For PHP4                                             |
  +----------------------------------------------------------------------+
  | Copyright (c) 2004-2005 The Sofee Development Team                   |
  +----------------------------------------------------------------------+
  | This source file is subject to the GNU Lesser Public License (LGPL), |
  | that is bundled with this package in the file LICENSE, and is        |
  | available at through the world-wide-web at                           |
  | http://www.fsf.org/copyleft/lesser.html                              |
  | If you did not receive a copy of the LGPL and are unable to          |
  | obtain it through the world-wide-web, you can get it by writing the  |
  | Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, |
  | MA 02111-1307, USA.                                                  |
  +----------------------------------------------------------------------+
  | Author: Justin Wu <ezdevelop@gmail.com>                              |
  +----------------------------------------------------------------------+
*/

namespace SprykerFeature\Zed\Transfer\Business\Model\External\Sofee;

/* $Id: SofeeXmlParser.php,v 1.3 2005/05/30 06:30:14 wenlong Exp $ */

/**
* Sofee XML Parser class - This is an XML parser based on PHP's "xml" extension.
*
* The SofeeXmlParser class provides a very simple and easily usable toolset to convert XML 
* to an array that can be processed with array iterators.
*
* @package		SofeeFramework
* @access		public
* @version		$Revision: 1.1 $
* @author		Justin Wu <wenlong@php.net>
* @homepage		http://www.sofee.cn
* @copyright	Copyright (c) 2004-2005 Sofee Development Team.(http://www.sofee.cn)
* @since		2005-05-30
* @see			PEAR:XML_Parser | SimpleXML extension
*/
class SofeeXmlParser {

    /**
    * XML parser handle
    *
    * @var		resource
    * @see		xml_parser_create()
    */
    protected $parser;

    /**
    * source encoding
    *
    * @var		string
    */
    protected $srcenc;

    /**
    * target encoding
    *
    * @var		string
    */
    protected $dstenc;

    /**
    * the original struct
    *
    * @access	protected
    * @var		array1
    */
    protected $_struct = array();

    /**
    * Constructor
    *
    * @access		public
    * @param		mixed		[$srcenc] source encoding
    * @param		mixed		[$dstenc] target encoding
    * @return		void
    * @since
    */
    public function SofeeXmlParser($srcenc = null, $dstenc = null)
    {
        $this->srcenc = $srcenc;
        $this->dstenc = $dstenc;

        // initialize the variable.
        $this->parser = null;
        $this->_struct = array();
    }

    /**
    * Free the resources
    *
    * @access		public
    * @return		void
    **/
    public function free()
    {
        if (isset($this->parser) && is_resource($this->parser)) {
            xml_parser_free($this->parser);
            unset($this->parser);
        }
    }

    /**
    * Parses the XML file
    *
    * @access		public
    * @param		string		[$file] the XML file name
    * @return		void
    * @since
    */
    public function parseFile($file)
    {
        $data = @file_get_contents($file) or die("Can't open file $file for reading!");
        $this->parseString($data);
    }

    /**
    * Parses a string.
    *
    * @access		public
    * @param		string		[$data] XML data
    * @return		void
    */
    public function parseString($data)
    {
        if ($this->srcenc === null) {
            $this->parser = @xml_parser_create() or die('Unable to create XML parser resource.');
        } else {
            $this->parser = @xml_parser_create($this->srcenc) or die('Unable to create XML parser resource with '. $this->srcenc .' encoding.');
        }

        if ($this->dstenc !== null) {
            @xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $this->dstenc) or die('Invalid target encoding');
        }
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);	// lowercase tags
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);		// skip empty tags
        if (!xml_parse_into_struct($this->parser, $data, $this->_struct)) {
            printf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($this->parser)),
                    xml_get_current_line_number($this->parser)
            );
            $this->free();
            exit();
        }

        $this->_count = count($this->_struct);
        $this->free();
    }

    /**
    * return the data struction
    *
    * @access		public
    * @return		array
    */
    public function getTree()
    {
        $i = 0;
        $tree = array();

        $tree = $this->addNode(
            $tree,
            $this->_struct[$i]['tag'],
            (isset($this->_struct[$i]['value'])) ? $this->_struct[$i]['value'] : '',
            (isset($this->_struct[$i]['attributes'])) ? $this->_struct[$i]['attributes'] : '',
            $this->getChild($i)
        );

        unset($this->_struct);
        return ($tree);
    }

    /**
    * recursion the children node data
    *
    * @access		public
    * @param		integer		[$i] the last struct index
    * @return		array
    */
    public function getChild(&$i)
    {
        // contain node data
        $children = array();

        // loop
        while (++$i < $this->_count) {
            // node tag name
            $tagname = $this->_struct[$i]['tag'];
            $value = isset($this->_struct[$i]['value']) ? $this->_struct[$i]['value'] : '';
            $attributes = isset($this->_struct[$i]['attributes']) ? $this->_struct[$i]['attributes'] : '';

            switch ($this->_struct[$i]['type']) {
                case 'open':
                    // node has more children
                    $child = $this->getChild($i);
                    // append the children data to the current node
                    $children = $this->addNode($children, $tagname, $value, $attributes, $child);
                    break;
                case 'complete':
                    // at end of current branch
                    $children = $this->addNode($children, $tagname, $value, $attributes);
                    break;
                case 'cdata':
                    // node has CDATA after one of it's children
                    $children['value'] .= $value;
                    break;
                case 'close':
                    // end of node, return collected data
                    return $children;
                    break;
            }

        }
        //return $children;
    }

    /**
    * Appends some values to an array
    *
    * @access		public
    * @param		array		[$target]
    * @param		string		[$key]
    * @param		string		[$value]
    * @param		array		[$attributes]
    * @param		array		[$child] the children
    * @return		void
    * @since
    */
    public function addNode($target, $key, $value = '', $attributes = '', $child = '')
    {
        if (!isset($target[$key]['value']) && !isset($target[$key][0])) {
            if ($child != '') {
                $target[$key] = $child;
            }
            if ($attributes != '') {
                foreach ($attributes as $k => $v) {
                    $target[$key][$k] = $v;
                }
            }

            $target[$key]['value'] = $value;
        } else {
            if (!isset($target[$key][0])) {
                // is string or other
                $oldvalue = $target[$key];
                $target[$key] = array();
                $target[$key][0] = $oldvalue;
                $index = 1;
            } else {
                // is array
                $index = count($target[$key]);
            }

            if ($child != '') {
                $target[$key][$index] = $child;
            }

            if ($attributes != '') {
                foreach ($attributes as $k => $v) {
                    $target[$key][$index][$k] = $v;
                }
            }
            $target[$key][$index]['value'] = $value;
        }
        return $target;
    }

}
