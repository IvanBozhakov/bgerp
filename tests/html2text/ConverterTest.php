<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-08-29 at 18:02:38.
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var type_Richtext
     */
    protected $Richtext;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->Richtext = cls::get('type_Richtext');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    /**
     * @covers html2text_Converter::_convert
     */
    public function test_MarkupInPre()
    {
        $result = html2text_Converter::toRichText('<pre><FONT COLOR=RED>!!! red BUG !!!</FONT></pre>');
        $expect = '[code]!!! red BUG !!![/code]';
        
        $this->assertEquals($expect, $result);
    }

    /**
     * @covers html2text_Converter::_convert
     */
    public function test_EntitiesInPre()
    {
        $result = html2text_Converter::toRichText('<pre>&lt; &#9829; \'</pre>');
        $heart  = html_entity_decode('&#9829;', NULL, 'utf-8');
        
        $expect = '[code]< ' . $heart . ' \'[/code]';
        $this->assertEquals($expect, $result);
        
        $result = (string)$this->Richtext->toVerbal($result);
        
        $this->assertContains("<pre class='richtext'>&lt; " . $heart . " '", $result);
    }

    /**
     * @covers html2text_Converter::_convert
     */
    public function test_PlaceholderInPre()
    {
        $result = html2text_Converter::toRichText('<pre>[#title#]</pre>');
        $expect = '[code][#title#][/code]';
        
        $this->assertEquals($expect, $result);

        $result = (string)$this->Richtext->toVerbal($result);
        
        $this->assertContains("<pre class='richtext'>&#91;#title#]", $result);
    }
}
