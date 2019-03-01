<?php namespace Test;

use App\Http\HogLatin;

class TranslateTest extends \Codeception\Test\Unit
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var HogLatin
     */
    private $translator;

    protected function _before()
    {
        $this->config = require __DIR__ . "/../config/app.php";
        $this->translator = new HogLatin($this->config['vowels'], $this->config['dialect']);
    }

    protected function _after()
    {
    }

    /**
     * Check is string
     */
    public function testIsString()
    {
        $this->assertIsString($this->translator->translate(""));
        $this->assertIsString($this->translator->translate(12312432432));
    }

    /**
     * Check is empty
     */
    public function testEmpty()
    {
        $this->assertEmpty($this->translator->translate(""));
    }

    /**
     * Test translations
     */
    public function testTranslation()
    {
        $this->assertEquals("Isthay", $this->translator->translate("This"));
        $this->assertNotEquals("Isthay", $this->translator->translate("Thiis"));
        $this->assertEquals("exampleay", $this->translator->translate("example"));
        $this->assertEquals("it`say", $this->translator->translate("it`s"));
        $this->assertNotEquals("t`siay", $this->translator->translate("it`s"));
        $this->assertEquals("ildrenchay", $this->translator->translate("children"));
        $this->assertEquals("Isthay isay anay exampleay.",
            $this->translator->translate("This is an example."));
        $this->assertEquals("it`say illysay,", $this->translator->translate("it`s silly,"));
        $this->assertEquals("", $this->translator->translate("кирилица"));
        $this->assertEquals("Isthay isay anay exampleay ofay Oghay Atinlay. Asay ouyay ancay eesay, it`say illysay, utbay otslay ofay unfay orfay ildrenchay.",
            $this->translator->translate("This is an example of Hog Latin. As you can see, it`s silly, but lots of fun for children."));
    }
}