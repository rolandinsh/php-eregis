<?php

namespace Rabus\EregShim;

use PHPUnit\Framework\TestCase;

class EregTest extends TestCase
{
    private $oldErrorReporting;

    protected function setUp()
    {
        $this->oldErrorReporting = \error_reporting(E_ALL & ~E_DEPRECATED);
    }

    protected function tearDown()
    {
        \error_reporting($this->oldErrorReporting);
    }

    /**
     * @dataProvider provideMatchingTestCases
     */
    public function testMatchingEregWithRegs($pattern, $string, $expectedCount, $expectedMatches)
    {
        $this->assertSame($expectedCount, \ereg($pattern, $string, $regs));
        $this->assertSame($expectedMatches, $regs);
    }

    /**
     * @dataProvider provideMatchingTestCases
     */
    public function testMatchingEregWithoutRegs($pattern, $string)
    {
        $this->assertSame(1, \ereg($pattern, $string));
    }

    public function provideMatchingTestCases()
    {
        return array(
            array(
                '.*nice and simple.*',
                'This is a nice and simple string',
                32,
                array('This is a nice and simple string')
            ),
            array(
                '.*(is).*(is).*',
                'This is a nice and simple string',
                32,
                array('This is a nice and simple string', 'is', 'is')
            ),
            array('..(a|b|c)(a|b|c)..', '--- ab ---', 6, array('- ab -', 'a', 'b')),
            array('()', '', 1, array(false, false)),
            array('()', 'abcdef', 1, array(false, false)),
            array('[x]|[^x]', 'abcdef', 1, array('a')),
            array(
                '(a{1})(a{1,}) (b{1,3}) (c+) (d?ddd|e)',
                '--- aaa bbb ccc ddd ---', 15,
                array('aaa bbb ccc ddd','a', 'aa', 'bbb', 'ccc', 'ddd')
            ),
            array('\\\\\`\^\.\[\$\(\)\|\*\+\?\{\\\'', '\\`^.[$()|*+?{\'', 14, array('\`^.[$()|*+?{\'')),
            // FIXME
            // array('\\a', 'a', 1, array('a')),
            array('[0-9][^0-9]', '2a', 2, array('2a')),
            array(
                '^[[:alnum:]]{62,62}$',
                '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                62,
                array('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
            ),
            array('^[[:digit:]]{5}', '0123456789', 5, array('01234')),
            array('[[:digit:]]{5}$', '0123456789', 5, array('56789')),
            array('[[:blank:]]{1,10}', "\n \t", 2, array(" \t")),
            array('[[:print:]]{3}', " a ", 3, array(' a ')),
        );
    }

    public function testLongMatch()
    {
        $this->assertSame(1, \ereg(str_repeat('(.)', 2048), str_repeat('x', 2048)));
        $this->assertSame(2048, \ereg(str_repeat('(.)', 2048), str_repeat('x', 2048), $regs));

        $this->assertCount(2049, $regs);
    }

    /**
     * @dataProvider provideNonMatchingTestCases
     */
    public function testNonMatchingEregWithRegs($pattern, $string)
    {
        $regs = 'original';

        $this->assertFalse(\ereg($pattern, $string, $regs));
        $this->assertSame('original', $regs);
    }

    /**
     * @dataProvider provideNonMatchingTestCases
     */
    public function testNonMatchingEregWithoutRegs($pattern, $string)
    {
        $this->assertFalse(\ereg($pattern, $string));
    }

    public function provideNonMatchingTestCases()
    {
        return array(
            array('.*doesn\'t exist.*','This is a nice and simple string'),
            array('A', 'a'),
            array('[A-Z]', '0'),
            array('(a){4}', 'aaa'),
            array('^a', 'ba'),
            array('b$', 'ba'),
            // FIXME
            // array('[:alpha:]', 'x'),
        );
    }
}
