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
 * Zend_View_Helper_FormRadioTest
 *
 * Tests formRadio helper
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormRadioTest extends PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->view = new Zend_View();
        $this->view->doctype('HTML4_LOOSE'); // Set default doctype
        $this->helper = new Zend_View_Helper_FormRadio();
        $this->helper->setView($this->view);
    }

    public function testRendersRadioLabelsWhenRenderingMultipleOptions()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $this->assertRegExp('#<label.*?>.*?' . $value . '.*?</label>#', $html, $html);
            $this->assertRegExp('#<label.*?>.*?<input.*?</label>#', $html, $html);
        }
    }

    public function testCanSpecifyRadioLabelPlacement()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('labelPlacement' => 'append')
        ));
        foreach ($options as $key => $value) {
            $this->assertRegExp('#<label.*?>.*?<input .*?' . $value . '</label>#', $html, $html);
        }

        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('labelPlacement' => 'prepend')
        ));
        foreach ($options as $key => $value) {
            $this->assertRegExp('#<label.*?>' . $value . '<input .*?</label>#', $html, $html);
        }
    }

    /**
     * @group ZF-3206
     */
    public function testSpecifyingLabelPlacementShouldNotOverwriteValue()
    {
        $options = array(
            'bar' => 'Bar',
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array(
                'labelPlacement' => 'append',
            )
        ));
        $this->assertRegExp('#<input[^>]*(checked="checked")#', $html, $html);
    }

    public function testCanSpecifyRadioLabelAttribs()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('labelClass' => 'testclass', 'label_id' => 'testid')
        ));

        foreach ($options as $key => $value) {
            $this->assertRegExp('#<label[^>]*?class="testclass"[^>]*>.*?' . $value . '#', $html, $html);
            $this->assertRegExp('#<label[^>]*?id="testid"[^>]*>.*?' . $value . '#', $html, $html);
        }
    }

    public function testCanSpecifyRadioSeparator()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'listsep' => '--FunkySep--',
        ));

        $this->assertStringContainsString('--FunkySep--', $html);
        $count = substr_count($html, '--FunkySep--');
        $this->assertEquals(2, $count);
    }

    /**
     * ZF-2513
     */
    public function testCanDisableAllRadios()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('disable' => true)
        ));

        $this->assertRegExp('/<input[^>]*?(disabled="disabled")/', $html, $html);
        $count = substr_count($html, 'disabled="disabled"');
        $this->assertEquals(3, $count);
    }

    /**
     * ZF-2513
     */
    public function testCanDisableIndividualRadios()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('disable' => array('bar'))
        ));

        $this->assertRegExp('/<input[^>]*?(value="bar")[^>]*(disabled="disabled")/', $html, $html);
        $count = substr_count($html, 'disabled="disabled"');
        $this->assertEquals(1, $count);
    }

    /**
     * ZF-2513
     */
    public function testCanDisableMultipleRadios()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
            'attribs' => array('disable' => array('foo', 'baz'))
        ));

        foreach (array('foo', 'baz') as $test) {
            $this->assertRegExp('/<input[^>]*?(value="' . $test . '")[^>]*?(disabled="disabled")/', $html, $html);
        }
        $this->assertNotRegExp('/<input[^>]*?(value="bar")[^>]*?(disabled="disabled")/', $html, $html);
        $count = substr_count($html, 'disabled="disabled"');
        $this->assertEquals(2, $count);
    }

    public function testLabelsAreEscapedByDefault()
    {
        $options = array(
            'bar' => '<b>Bar</b>',
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'options' => $options,
        ));

        $this->assertStringNotContainsString($options['bar'], $html);
        $this->assertStringContainsString('&lt;b&gt;Bar&lt;/b&gt;', $html);
    }

    public function testXhtmlLabelsAreAllowed()
    {
        $options = array(
            'bar' => '<b>Bar</b>',
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'options' => $options,
            'attribs' => array('escape' => false)
        ));

        $this->assertStringContainsString($options['bar'], $html);
    }

    /**
     * ZF-1666
     */
    public function testDoesNotRenderHiddenElements()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'options' => $options,
        ));

        $this->assertNotRegExp('/<input[^>]*?(type="hidden")/', $html);
    }

    public function testSpecifyingAValueThatMatchesAnOptionChecksIt()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));

        if (!preg_match('/(<input[^>]*?(value="bar")[^>]*>)/', $html, $matches)) {
            $this->fail('Radio for a given option was not found?');
        }
        $this->assertStringContainsString('checked="checked"', $matches[1], var_export($matches, 1));
    }

    public function testOptionsWithMatchesInAnArrayOfValuesAreChecked()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => array('foo', 'baz'),
            'options' => $options,
        ));

        foreach (array('foo', 'baz') as $value) {
            if (!preg_match('/(<input[^>]*?(value="' . $value . '")[^>]*>)/', $html, $matches)) {
                $this->fail('Radio for a given option was not found?');
            }
            $this->assertStringContainsString('checked="checked"', $matches[1], var_export($matches, 1));
        }
    }

    public function testEachRadioShouldHaveIdCreatedByAppendingFilteredValue()
    {
        $options = array(
            'foo bar' => 'Foo',
            'bar baz' => 'Bar',
            'baz'     => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo[]',
            'value'   => 'bar',
            'options' => $options,
        ));

        $filter = new Zend_Filter_Alnum();
        foreach ($options as $key => $value) {
            $id = 'foo-' . $filter->filter($key);
            $this->assertRegExp('/<input([^>]*)(id="' . $id . '")/', $html);
        }
    }

    public function testEachRadioShouldUseAttributeIdWhenSpecified()
    {
        $options = array(
            'foo bar' => 'Foo',
            'bar baz' => 'Bar',
            'baz'     => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo[bar]',
            'value'   => 'bar',
            'attribs' => array('id' => 'foo-bar'),
            'options' => $options,
        ));

        $filter = new Zend_Filter_Alnum();
        foreach ($options as $key => $value) {
            $id = 'foo-bar-' . $filter->filter($key);
            $this->assertRegExp('/<input([^>]*)(id="' . $id . '")/', $html);
        }
    }

    /**
     * @group ZF-5681
     */
    public function testRadioLabelDoesNotContainHardCodedStyle()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        $this->assertStringNotContainsString('style="white-space: nowrap;"', $html);
    }

    /**
     * @group ZF-8709
     */
    public function testRadioLabelContainsNotForAttributeTag()
    {
        $actual = $this->helper->formRadio(
            array(
                 'name'    => 'foo',
                 'options' => array(
                     'bar' => 'Bar',
                     'baz' => 'Baz'
                 ),
            )
        );

        $expected = '<label><input type="radio" name="foo" id="foo-bar" value="bar">Bar</label><br>'
                  . "\n"
                  . '<label><input type="radio" name="foo" id="foo-baz" value="baz">Baz</label>';

        $this->assertSame($expected, $actual);
    }

    /**
     * @group ZF-4191
     */
    public function testDashesShouldNotBeFilteredFromId()
    {
        $name    = 'Foo';
        $options = array(
            -1 => 'Test -1',
             0 => 'Test 0',
             1 => 'Test 1'
        );

        $formRadio = new Zend_View_Helper_FormRadio();
        $formRadio->setView(new Zend_View());
        $html = $formRadio->formRadio($name, -1, null, $options);
        foreach ($options as $key => $value) {
            $fid = "{$name}-{$key}";
            $this->assertRegExp('/<input([^>]*)(id="' . $fid . '")/', $html);
        }

        // Assert that radio for value -1 is the selected one
        $this->assertRegExp('/<input([^>]*)(id="' . $name . '--1")([^>]*)(checked="checked")/', $html);
    }

    /**
     * @group ZF-11477
     */
    public function testRendersAsHtmlByDefault()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'options' => $options,
        ));

        $this->assertStringContainsString('value="foo">', $html);
        $this->assertStringContainsString('value="bar">', $html);
        $this->assertStringContainsString('value="baz">', $html);
    }

    /**
     * @group ZF-11477
     */
    public function testCanRendersAsXHtml()
    {
        $this->view->doctype('XHTML1_STRICT');
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formRadio(array(
            'name'    => 'foo',
            'options' => $options,
        ));
        $this->assertStringContainsString('value="foo" />', $html);
        $this->assertStringContainsString('value="bar" />', $html);
        $this->assertStringContainsString('value="baz" />', $html);
    }

    /**
     * @group ZF-11620
     */
    public function testSeparatorCanRendersAsXhtmlByDefault()
    {
        $this->view->doctype('XHTML1_STRICT');
        $options = array(
             'foo' => 'Foo',
             'bar' => 'Bar',
             'baz' => 'Baz'
         );
        $html = $this->helper->formRadio(array(
             'name'    => 'foo',
             'value'   => 'bar',
             'options' => $options,
         ));

        $this->assertStringContainsString('<br />', $html);
        $count = substr_count($html, '<br />');
        $this->assertEquals(2, $count);
    }

    /**
     * @group ZF-11620
     */
    public function testeparatorCanRendersAsHtml()
    {
        $this->view->doctype('HTML4_STRICT');
        $options = array(
             'foo' => 'Foo',
             'bar' => 'Bar',
             'baz' => 'Baz'
         );
        $html = $this->helper->formRadio(array(
             'name'    => 'foo',
             'value'   => 'bar',
             'options' => $options,
         ));

        $this->assertStringContainsString('<br>', $html);
        $count = substr_count($html, '<br>');
        $this->assertEquals(2, $count);
    }
}
