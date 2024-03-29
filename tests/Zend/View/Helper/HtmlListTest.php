<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_HtmlListTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Zend_View_Helper_HtmlList
     */
    public $helper;

    protected $view;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp(): void
    {
        $this->view   = new Zend_View();
        $this->helper = new Zend_View_Helper_HtmlList();
        $this->helper->setView($this->view);
    }

    public function tearDown(): void
    {
        unset($this->helper);
    }

    public function testMakeUnorderedList()
    {
        $items = array('one', 'two', 'three');

        $list = $this->helper->htmlList($items);

        $this->assertStringContainsString('<ul>', $list);
        $this->assertStringContainsString('</ul>', $list);
        foreach ($items as $item) {
            $this->assertStringContainsString('<li>' . $item . '</li>', $list);
        }
    }

    public function testMakeOrderedList()
    {
        $items = array('one', 'two', 'three');

        $list = $this->helper->htmlList($items, true);

        $this->assertStringContainsString('<ol>', $list);
        $this->assertStringContainsString('</ol>', $list);
        foreach ($items as $item) {
            $this->assertStringContainsString('<li>' . $item . '</li>', $list);
        }
    }

    public function testMakeUnorderedListWithAttribs()
    {
        $items   = array('one', 'two', 'three');
        $attribs = array('class' => 'selected', 'name' => 'list');

        $list = $this->helper->htmlList($items, false, $attribs);

        $this->assertStringContainsString('<ul', $list);
        $this->assertStringContainsString('class="selected"', $list);
        $this->assertStringContainsString('name="list"', $list);
        $this->assertStringContainsString('</ul>', $list);
        foreach ($items as $item) {
            $this->assertStringContainsString('<li>' . $item . '</li>', $list);
        }
    }

    public function testMakeOrderedListWithAttribs()
    {
        $items   = array('one', 'two', 'three');
        $attribs = array('class' => 'selected', 'name' => 'list');

        $list = $this->helper->htmlList($items, true, $attribs);

        $this->assertStringContainsString('<ol', $list);
        $this->assertStringContainsString('class="selected"', $list);
        $this->assertStringContainsString('name="list"', $list);
        $this->assertStringContainsString('</ol>', $list);
        foreach ($items as $item) {
            $this->assertStringContainsString('<li>' . $item . '</li>', $list);
        }
    }

    /*
     * @group ZF-5018
     */
    public function testMakeNestedUnorderedList()
    {
        $items = array('one', array('four', 'five', 'six'), 'two', 'three');

        $list = $this->helper->htmlList($items);

        $this->assertStringContainsString('<ul>' . Zend_View_Helper_HtmlList::EOL, $list);
        $this->assertStringContainsString('</ul>' . Zend_View_Helper_HtmlList::EOL, $list);
        $this->assertStringContainsString('one<ul>' . Zend_View_Helper_HtmlList::EOL . '<li>four', $list);
        $this->assertStringContainsString('<li>six</li>' . Zend_View_Helper_HtmlList::EOL . '</ul>' .
            Zend_View_Helper_HtmlList::EOL . '</li>' . Zend_View_Helper_HtmlList::EOL . '<li>two', $list);
    }

    /*
     * @group ZF-5018
     */
    public function testMakeNestedDeepUnorderedList()
    {
        $items = array('one', array('four', array('six', 'seven', 'eight'), 'five'), 'two', 'three');

        $list = $this->helper->htmlList($items);

        $this->assertStringContainsString('<ul>' . Zend_View_Helper_HtmlList::EOL, $list);
        $this->assertStringContainsString('</ul>' . Zend_View_Helper_HtmlList::EOL, $list);
        $this->assertStringContainsString('one<ul>' . Zend_View_Helper_HtmlList::EOL . '<li>four', $list);
        $this->assertStringContainsString('<li>four<ul>' . Zend_View_Helper_HtmlList::EOL . '<li>six', $list);
        $this->assertStringContainsString('<li>five</li>' . Zend_View_Helper_HtmlList::EOL . '</ul>' .
            Zend_View_Helper_HtmlList::EOL . '</li>' . Zend_View_Helper_HtmlList::EOL . '<li>two', $list);
    }

    public function testListWithValuesToEscapeForZF2283()
    {
        $items = array('one <small> test', 'second & third', 'And \'some\' "final" test');

        $list = $this->helper->htmlList($items);

        $this->assertStringContainsString('<ul>', $list);
        $this->assertStringContainsString('</ul>', $list);

        $this->assertStringContainsString('<li>one &lt;small&gt; test</li>', $list);
        $this->assertStringContainsString('<li>second &amp; third</li>', $list);
        $this->assertStringContainsString('<li>And \'some\' &quot;final&quot; test</li>', $list);
    }

    public function testListEscapeSwitchedOffForZF2283()
    {
        $items = array('one <b>small</b> test');

        $list = $this->helper->htmlList($items, false, false, false);

        $this->assertStringContainsString('<ul>', $list);
        $this->assertStringContainsString('</ul>', $list);

        $this->assertStringContainsString('<li>one <b>small</b> test</li>', $list);
    }

    /**
     * @group ZF-2527
     */
    public function testEscapeFlagHonoredForMultidimensionalLists()
    {
        $items = array('<b>one</b>', array('<b>four</b>', '<b>five</b>', '<b>six</b>'), '<b>two</b>', '<b>three</b>');

        $list = $this->helper->htmlList($items, false, false, false);

        foreach ($items[1] as $item) {
            $this->assertStringContainsString($item, $list);
        }
    }

    /**
     * @group ZF-2527
     * Added the s modifier to match newlines after @see ZF-5018
     */
    public function testAttribsPassedIntoMultidimensionalLists()
    {
        $items = array('one', array('four', 'five', 'six'), 'two', 'three');

        $list = $this->helper->htmlList($items, false, array('class' => 'foo'));

        foreach ($items[1] as $item) {
            $this->assertMatchesRegularExpression('#<ul[^>]*?class="foo"[^>]*>.*?(<li>' . $item . ')#s', $list);
        }
    }

    /**
     * @group ZF-2870
     */
    /*public function testEscapeFlagShouldBePassedRecursively()
    {
        $items = array(
            '<b>one</b>',
            array(
                '<b>four</b>',
                '<b>five</b>',
                '<b>six</b>',
                array(
                    '<b>two</b>',
                    '<b>three</b>',
                ),
            ),
        );

        $list = $this->helper->htmlList($items, false, false, false);

        $this->assertStringContainsString('<ul>', $list);
        $this->assertStringContainsString('</ul>', $list);

        $this->markTestSkipped('Wrong array_walk_recursive behavior.');

        array_walk_recursive($items, array($this, 'validateItems'), $list);
    }*/

    public function validateItems($value, $key, $userdata)
    {
        $this->assertStringContainsString('<li>' . $value, $userdata);
    }
}
