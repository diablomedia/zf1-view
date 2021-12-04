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
 * Test class for Zend_View_Helper_Fieldset
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FieldsetTest extends PHPUnit\Framework\TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->view   = new Zend_View();
        $this->helper = new Zend_View_Helper_Fieldset();
        $this->helper->setView($this->view);
        ob_start();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown(): void
    {
        ob_end_clean();
    }

    public function testFieldsetHelperCreatesFieldsetWithProvidedContent()
    {
        $html = $this->helper->fieldset('foo', 'foobar');
        $this->assertMatchesRegularExpression('#<fieldset[^>]+id="foo".*?>#', $html);
        $this->assertStringContainsString('</fieldset>', $html);
        $this->assertStringContainsString('foobar', $html);
    }

    public function testProvidingLegendOptionToFieldsetCreatesLegendTag()
    {
        $html = $this->helper->fieldset('foo', 'foobar', array('legend' => 'Great Scott!'));
        $this->assertMatchesRegularExpression('#<legend>Great Scott!</legend>#', $html);
    }

    /**
     * @group ZF-2913
     */
    public function testEmptyLegendShouldNotRenderLegendTag()
    {
        foreach (array(null, '', ' ', false) as $legend) {
            $html = $this->helper->fieldset('foo', 'foobar', array('legend' => $legend));
            $this->assertStringNotContainsString('<legend>', $html, 'Failed with value ' . var_export($legend, 1) . ': ' . $html);
        }
    }

    /**
     * @group ZF-3632
     */
    public function testHelperShouldAllowDisablingEscapingOfLegend()
    {
        $html = $this->helper->fieldset('foo', 'foobar', array('legend' => '<b>Great Scott!</b>', 'escape' => false));
        $this->assertMatchesRegularExpression('#<legend><b>Great Scott!</b></legend>#', $html, $html);
    }
}
