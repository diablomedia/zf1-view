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
 * Test class for Zend_View_Helper_FormMultiCheckbox
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormMultiCheckboxTest extends PHPUnit\Framework\TestCase
{
    protected $view;
    protected $helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp(): void
    {
        if (Zend_Registry::isRegistered('Zend_View_Helper_Doctype')) {
            $registry = Zend_Registry::getInstance();
            unset($registry['Zend_View_Helper_Doctype']);
        }
        $this->view   = new Zend_View();
        $this->helper = new Zend_View_Helper_FormMultiCheckbox();
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

    public function testMultiCheckboxHelperRendersLabelledCheckboxesForEachOption()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#((<label[^>]*>.*?)(<input[^>]*?("' . $key . '").*?>)(.*?</label>))#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringContainsString($value, $matches[5], var_export($matches, 1));
            $this->assertStringContainsString('type="checkbox"', $matches[3], var_export($matches, 1));
            $this->assertStringContainsString('name="foo[]"', $matches[3], var_export($matches, 1));
            $this->assertStringContainsString('value="' . $key . '"', $matches[3], var_export($matches, 1));
        }
    }

    public function testRendersAsHtmlByDefault()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#(<input[^>]*?("' . $key . '").*?>)#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringNotContainsString(' />', $matches[1]);
        }
    }

    public function testCanRendersAsXHtml()
    {
        $this->view->doctype('XHTML1_STRICT');
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#(<input[^>]*?("' . $key . '").*?>)#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringContainsString(' />', $matches[1]);
        }
    }
}
