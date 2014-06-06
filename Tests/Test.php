<?php

require_once __DIR__ . '/../Classes/Famelo/StringScore/String.php';
use Famelo\StringScore\String;

class StringScoreTest extends PHPUnit_Framework_TestCase {

    public function testSimpleStringScoring() {
        $this->assertEquals(String::score("hello world", "axl"), 0);
        $this->assertEquals(String::score("hello world", "ow"), 0.35454545454545455);
        $this->assertEquals(String::score("hello world", "e"), 0.1090909090909091); // (single letter match)
		$this->assertEquals(String::score("hello world", "h"), 0.586363636364); // (single letter match plus bonuses for beginning of word and beginning of phrase)
		$this->assertEquals(String::score("hello world", "he"), 0.622727272727);
		$this->assertEquals(String::score("hello world", "hel"), 0.659090909091);
		$this->assertEquals(String::score("hello world", "hell"), 0.695454545455);
		$this->assertEquals(String::score("hello world", "hello"), 0.731818181818);

		$this->assertEquals(String::score("hello world", "hello worl"), 0.913636363636);
		$this->assertEquals(String::score("hello world", "hello world"), 1);
    }

    public function testFuzzyStringScoring() {
		$this->assertEquals(String::score("hello world", "hello wor1"), 0);
		$this->assertEquals(String::score("hello world", "hello wor1", 0.5), 0.6081818181818182);
    }

    public function testStringLengthScoring() {
		$this->assertGreaterThan(String::score("Hello", "h"), String::score("He", "h"));
    }

    public function testStringCaseScoring() {
		$this->assertGreaterThan(String::score("Hello", "h"), String::score("Hello", "H"));
    }

    public function testStringAcronymsScoring() {
		$this->assertGreaterThan(String::score("Hillsdale Michigan", "Hills"), String::score("Hillsdale Michigan", "HiMi"));
		$this->assertlessThan(String::score("Hillsdale Michigan", "Hillsd"), String::score("Hillsdale Michigan", "HiMi"));
    }
}

?>