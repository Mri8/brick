<?php

namespace Brick\Tests\Math\BigInteger;

use Brick\Math\ArithmeticException;
use Brick\Math\Internal\Calculator;
use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;

/**
 * Unit tests for class BigInteger.
 */
abstract class AbstractTestCase extends \Brick\Tests\Math\AbstractTestCase
{
    /**
     * @dataProvider providerOf
     *
     * @param string|number $value    The value to convert to a BigInteger.
     * @param string        $expected The expected string value of the result.
     */
    public function testOf($value, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigInteger::of($value));
    }

    /**
     * @return array
     */
    public function providerOf()
    {
        return [
            [0, '0'],
            [1, '1'],
            [-1, '-1'],
            [123456789, '123456789'],
            [-123456789, '-123456789'],
            [PHP_INT_MAX, (string) PHP_INT_MAX],
            [~PHP_INT_MAX, (string) ~PHP_INT_MAX],

            [0.0, '0'],
            [1.0, '1'],
            [-0.0, '0'],
            [-1.0, '-1'],

            [1.2e5, '120000'],
            [-1.2e5, '-120000'],

            ['0', '0'],
            ['+0', '0'],
            ['-0', '0'],

            ['1', '1'],
            ['+1', '1'],
            ['-1', '-1'],

            ['00', '0'],
            ['+00', '0'],
            ['-00', '0'],

            ['01', '1'],
            ['+01', '1'],
            ['-01', '-1'],

            ['123456789012345678901234567890', '123456789012345678901234567890'],
            ['+123456789012345678901234567890', '123456789012345678901234567890'],
            ['-123456789012345678901234567890', '-123456789012345678901234567890'],

            ['0123456789012345678901234567890', '123456789012345678901234567890'],
            ['+0123456789012345678901234567890', '123456789012345678901234567890'],
            ['-0123456789012345678901234567890', '-123456789012345678901234567890'],

            ['00123456789012345678901234567890', '123456789012345678901234567890'],
            ['+00123456789012345678901234567890', '123456789012345678901234567890'],
            ['-00123456789012345678901234567890', '-123456789012345678901234567890'],
        ];
    }

    public function testOfBigIntegerReturnsThis()
    {
        $decimal = BigInteger::of(123);

        $this->assertSame($decimal, BigInteger::of($decimal));
    }

    /**
     * @dataProvider providerOfInvalidValueThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param string|number $value
     */
    public function testOfInvalidValueThrowsException($value)
    {
        BigInteger::of($value);
    }

    /**
     * @return array
     */
    public function providerOfInvalidValueThrowsException()
    {
        return [
            [1.1],
            [1e40],

            [''],
            ['a'],
            [' 1'],
            ['1 '],
            ['1.'],
            ['1.0'],
            ['+'],
            ['-'],
            ['+a'],
            ['-a']
        ];
    }

    /**
     * @dataProvider providerParse
     *
     * @param string  $number   The number to create.
     * @param integer $base     The base of the number.
     * @param string  $expected The expected result in base 10.
     */
    public function testParse($number, $base, $expected)
    {
        $this->assertSame($expected, (string) BigInteger::parse($number, $base));
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['0', 10, '0'],
            ['-0', 10, '0'],
            ['+0', 10, '0'],
            ['00', 16, '0'],
            ['-00', 16, '0'],
            ['+00', 16, '0'],

            ['123', 10, '123'],
            ['+456', 10, '456'],
            ['-789', 10, '-789'],
            ['0123', 10, '123'],
            ['+0456', 10, '456'],
            ['-0789', 10, '-789'],

            ['110011001100110011001111', 36, '640998479760579495168036691627608949'],
            ['110011001100110011001111', 35, '335582856048758779730579523833856636'],
            ['110011001100110011001111', 34, '172426711023004493064981145981549295'],
            ['110011001100110011001111', 33, '86853227285668653965326574185738990'],
            ['110011001100110011001111', 32, '42836489934972583913564073319498785'],
            ['110011001100110011001111', 31, '20658924711984480538771889603666144'],
            ['110011001100110011001111', 30, '9728140488839986222205212599027931'],
            ['110011001100110011001111', 29, '4465579470019956787945275674107410'],
            ['110011001100110011001111', 28, '1994689924537781753408144504465645'],
            ['110011001100110011001111', 27, '865289950909412968716094193925700'],
            ['110011001100110011001111', 26, '363729369583879309352831568000039'],
            ['110011001100110011001111', 25, '147793267388865354156500488297526'],
            ['110011001100110011001111', 24, '57888012016107577099138793486425'],
            ['110011001100110011001111', 23, '21788392294523974761749372677800'],
            ['110011001100110011001111', 22, '7852874701996329566765721637715'],
            ['110011001100110011001111', 21, '2699289081943123258094476428634'],
            ['110011001100110011001111', 20, '880809345058406615041344008421'],
            ['110011001100110011001111', 19, '271401690926468032718781859340'],
            ['110011001100110011001111', 18, '78478889737009209699633503455'],
            ['110011001100110011001111', 17, '21142384915931646646976872830'],
            ['110011001100110011001111', 16, '5261325448418072742917574929'],
            ['110011001100110011001111', 15, '1197116069565850925807253616'],
            ['110011001100110011001111', 14, '245991074299834917455374155'],
            ['110011001100110011001111', 13, '44967318722190498361960610'],
            ['110011001100110011001111', 12, '7177144825886069940574045'],
            ['110011001100110011001111', 11, '976899716207148313491924'],
            ['110011001100110011001111', 10, '110011001100110011001111'],
            ['110011001100110011001111', 9, '9849210196991880028870'],
            ['110011001100110011001111', 8, '664244955832213832265'],
            ['110011001100110011001111', 7, '31291601125492514360'],
            ['110011001100110011001111', 6, '922063395565287619'],
            ['110011001100110011001111', 5, '14328039609468906'],
            ['110011001100110011001111', 4, '88305875046485'],
            ['110011001100110011001111', 3, '127093291420'],
            ['110011001100110011001111', 2, '13421775'],

            ['ZyXwVuTsRqPoNmLkJiHgFeDcBa9876543210', 36, '106300512100105327644605138221229898724869759421181854980'],
            ['YxWvUtSrQpOnMlKjIhGfEdCbA9876543210', 35, '1101553773143634726491620528194292510495517905608180485'],
            ['XwVuTsRqPoNmLkJiHgFeDcBa9876543210', 34, '11745843093701610854378775891116314824081102660800418'],
            ['WvUtSrQpOnMlKjIhGfEdCbA9876543210', 33, '128983956064237823710866404905431464703849549412368'],
            ['VuTsRqPoNmLkJiHgFeDcBa9876543210', 32, '1459980823972598128486511383358617792788444579872'],
            ['UtSrQpOnMlKjIhGfEdCbA9876543210', 31, '17050208381689099029767742314582582184093573615'],
            ['TsRqPoNmLkJiHgFeDcBa9876543210', 30, '205646315052919334126040428061831153388822830'],
            ['SrQpOnMlKjIhGfEdCbA9876543210', 29, '2564411043271974895869785066497940850811934'],
            ['RqPoNmLkJiHgFeDcBa9876543210', 28, '33100056003358651440264672384704297711484'],
            ['QpOnMlKjIhGfEdCbA9876543210', 27, '442770531899482980347734468443677777577'],
            ['PoNmLkJiHgFeDcBa9876543210', 26, '6146269788878825859099399609538763450'],
            ['OnMlKjIhGfEdCbA9876543210', 25, '88663644327703473714387251271141900'],
            ['NmLkJiHgFeDcBa9876543210', 24, '1331214537196502869015340298036888'],
            ['MlKjIhGfEdCbA9876543210', 23, '20837326537038308910317109288851'],
            ['LkJiHgFeDcBa9876543210', 22, '340653664490377789692799452102'],
            ['KjIhGfEdCbA9876543210', 21, '5827980550840017565077671610'],
            ['JiHgFeDcBa9876543210', 20, '104567135734072022160664820'],
            ['IhGfEdCbA9876543210', 19, '1972313422155189164466189'],
            ['HgFeDcBa9876543210', 18, '39210261334551566857170'],
            ['GfEdCbA9876543210', 17, '824008854613343261192'],
            ['FeDcBa9876543210', 16, '18364758544493064720'],
            ['EdCbA9876543210', 15, '435659737878916215'],
            ['DcBa9876543210', 14, '11046255305880158'],
            ['CbA9876543210', 13, '300771807240918'],
            ['Ba9876543210', 12, '8842413667692'],
            ['A9876543210', 11, '282458553905'],
            ['9876543210', 10, '9876543210'],
            ['876543210', 9, '381367044'],
            ['76543210', 8, '16434824'],
            ['6543210', 7, '800667'],
            ['543210', 6, '44790'],
            ['43210', 5, '2930'],
            ['3210', 4, '228'],
            ['210', 3, '21'],
            ['10', 2, '2'],
        ];
    }

    /**
     * @dataProvider providerParseInvalidValueThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param string  $value
     * @param integer $base
     */
    public function testParseInvalidValueThrowsException($value, $base)
    {
        BigInteger::parse($value, $base);
    }

    /**
     * @return array
     */
    public function providerParseInvalidValueThrowsException()
    {
        return [
            ['', 10],
            [' ', 10],
            ['+', 10],
            ['-', 10],
            ['1 ', 10],
            [' 1', 10],

            ['Z', 35],
            ['y', 34],
            ['X', 33],
            ['w', 32],
            ['V', 31],
            ['u', 30],
            ['T', 29],
            ['s', 28],
            ['R', 27],
            ['q', 26],
            ['P', 25],
            ['o', 24],
            ['N', 23],
            ['m', 22],
            ['L', 21],
            ['k', 20],
            ['J', 19],
            ['i', 18],
            ['H', 17],
            ['g', 16],
            ['F', 15],
            ['e', 14],
            ['D', 13],
            ['c', 12],
            ['B', 11],
            ['a', 10],
            ['9', 9],
            ['8', 8],
            ['7', 7],
            ['6', 6],
            ['5', 5],
            ['4', 4],
            ['3', 3],
            ['2', 2]
        ];
    }

    public function testZero()
    {
        $this->assertBigIntegerEquals('0', BigInteger::zero());
    }

    /**
     * @dataProvider providerPlus
     *
     * @param string $a The base number.
     * @param string $b The number to add.
     * @param string $r The expected result.
     */
    public function testPlus($a, $b, $r)
    {
        $this->assertBigIntegerEquals($r, BigInteger::of($a)->plus($b));
    }

    /**
     * @return array
     */
    public function providerPlus()
    {
        return [
            ['3493049309220392055810', '9918493493849898938928310121', '9918496986899208159320365931'],
            ['546254089287665464650654', '-4654654565726542654005465', '-4108400476438877189354811'],
            ['-54654654625426504062224', '406546504670332465465435004', '406491850015707038961372780'],
            ['-78706406576549688403246', '-3064672987984605465406546', '-3143379394561155153809792']
        ];
    }

    /**
     * @dataProvider providerMinus
     *
     * @param string $a The base number.
     * @param string $b The number to subtract.
     * @param string $r The expected result.
     */
    public function testMinus($a, $b, $r)
    {
        $this->assertBigIntegerEquals($r, BigInteger::of($a)->minus($b));
    }

    /**
     * @return array
     */
    public function providerMinus()
    {
        return [
            ['879798276565798787646', '2345178709879804654605406456', '-2345177830081528088806618810'],
            ['99465465545004066406868767', '-79870987954654608076067608768', '79970453420199612142474477535'],
            ['-46465465478979879230745664', '21316504468760001807687078994', '-21362969934238981686917824658'],
            ['-2154799048440940949896046', '-9000454956465465424345404846624', '9000452801666416983404454950578']
        ];
    }

    /**
     * @dataProvider providerMultipliedBy
     *
     * @param string $a The base number.
     * @param string $b The number to multiply.
     * @param string $r The expected result.
     */
    public function testMultipliedBy($a, $b, $r)
    {
        $this->assertBigIntegerEquals($r, BigInteger::of($a)->multipliedBy($b));
    }

    /**
     * @return array
     */
    public function providerMultipliedBy()
    {
        return [
            ['15892588375910581333', '2485910409339228962451', '39507550875019745254366764864945838527183'],
            ['341581435989834012309', '-91050393818389238433', '-31101124267925302088072082300643257871797'],
            ['-1204902920503999920003', '1984389583950290232332', '-2390996805119422027350037939263960284136996'],
            ['-991230349304902390122', '-3483910549230593053437', '3453357870660875087266990729629471366949314']
        ];
    }

    /**
     * @dataProvider providerDividedBy
     *
     * @param string $a The base number.
     * @param string $b The number to divide.
     * @param string $r The expected result.
     */
    public function testDividedBy($a, $b, $r)
    {
        $this->assertBigIntegerEquals($r, BigInteger::of($a)->dividedBy($b, RoundingMode::DOWN));
    }

    /**
     * @return array
     */
    public function providerDividedBy()
    {
        return [
            ['1282493059039502950823948435791053205342314', '24342491090593053', '52685366270303839158198740'],
            ['1000000000000000000000000000000000000000000000', '7777777777777777', '128571428571428584285714285714'],
            ['999999999999999999999999999999999999999999999', '22221222222', '45002025091579141312274843781092897'],
            ['49283205308081983923480483094304390249024223', '-23981985358744892239240813', '-2055009398548863185'],
            ['-8378278174814983902084304176539029302438924', '384758527893793829309012129991', '-21775419041855'],
            ['-444444444444444444444444444444444444411111', '-33333333333333', '13333333333333466666666666667']
        ];
    }

    /**
     * @dataProvider providerRoundingMode
     *
     * @param integer     $roundingMode The rounding mode.
     * @param string      $number       The number to round.
     * @param string|null $ten          The expected rounding divided by 10, or null if an exception is expected.
     * @param string|null $hundred      The expected rounding divided by 100 or null if an exception is expected.
     * @param string|null $thousand     The expected rounding divided by 1000, or null if an exception is expected.
     */
    public function testRoundingMode($roundingMode, $number, $ten, $hundred, $thousand)
    {
        $number = BigInteger::of($number);

        $this->doTestRoundingMode($roundingMode, $number, '1', $ten, $hundred, $thousand);
        $this->doTestRoundingMode($roundingMode, $number->negated(), '-1', $ten, $hundred, $thousand);
    }

    /**
     * @param integer     $roundingMode The rounding mode.
     * @param BigInteger  $number       The number to round.
     * @param string      $divisor      The divisor.
     * @param string|null $ten          The expected rounding to a scale of two, or null if an exception is expected.
     * @param string|null $hundred          The expected rounding to a scale of one, or null if an exception is expected.
     * @param string|null $thousand         The expected rounding to a scale of zero, or null if an exception is expected.
     */
    private function doTestRoundingMode($roundingMode, BigInteger $number, $divisor, $ten, $hundred, $thousand)
    {
        foreach ([$ten, $hundred, $thousand] as $expected) {
            $divisor .= '0';

            if ($expected === null) {
                $this->setExpectedException(ArithmeticException::class);
            }

            $actual = $number->dividedBy($divisor, $roundingMode);

            if ($expected !== null) {
                $this->assertBigIntegerEquals($expected, $actual);
            }
        }
    }

    /**
     * @return array
     */
    public function providerRoundingMode()
    {
        return [
            [RoundingMode::UP,  '3501',  '351',  '36',  '4'],
            [RoundingMode::UP,  '3500',  '350',  '35',  '4'],
            [RoundingMode::UP,  '3499',  '350',  '35',  '4'],
            [RoundingMode::UP,  '3001',  '301',  '31',  '4'],
            [RoundingMode::UP,  '3000',  '300',  '30',  '3'],
            [RoundingMode::UP,  '2999',  '300',  '30',  '3'],
            [RoundingMode::UP,  '2501',  '251',  '26',  '3'],
            [RoundingMode::UP,  '2500',  '250',  '25',  '3'],
            [RoundingMode::UP,  '2499',  '250',  '25',  '3'],
            [RoundingMode::UP,  '2001',  '201',  '21',  '3'],
            [RoundingMode::UP,  '2000',  '200',  '20',  '2'],
            [RoundingMode::UP,  '1999',  '200',  '20',  '2'],
            [RoundingMode::UP,  '1501',  '151',  '16',  '2'],
            [RoundingMode::UP,  '1500',  '150',  '15',  '2'],
            [RoundingMode::UP,  '1499',  '150',  '15',  '2'],
            [RoundingMode::UP,  '1001',  '101',  '11',  '2'],
            [RoundingMode::UP,  '1000',  '100',  '10',  '1'],
            [RoundingMode::UP,   '999',  '100',  '10',  '1'],
            [RoundingMode::UP,   '501',   '51',   '6',  '1'],
            [RoundingMode::UP,   '500',   '50',   '5',  '1'],
            [RoundingMode::UP,   '499',   '50',   '5',  '1'],
            [RoundingMode::UP,     '1',    '1',   '1',  '1'],
            [RoundingMode::UP,     '0',    '0',   '0',  '0'],
            [RoundingMode::UP,    '-1',   '-1',  '-1', '-1'],
            [RoundingMode::UP,  '-499',  '-50',  '-5', '-1'],
            [RoundingMode::UP,  '-500',  '-50',  '-5', '-1'],
            [RoundingMode::UP,  '-501',  '-51',  '-6', '-1'],
            [RoundingMode::UP,  '-999', '-100', '-10', '-1'],
            [RoundingMode::UP, '-1000', '-100', '-10', '-1'],
            [RoundingMode::UP, '-1001', '-101', '-11', '-2'],
            [RoundingMode::UP, '-1499', '-150', '-15', '-2'],
            [RoundingMode::UP, '-1500', '-150', '-15', '-2'],
            [RoundingMode::UP, '-1501', '-151', '-16', '-2'],
            [RoundingMode::UP, '-1999', '-200', '-20', '-2'],
            [RoundingMode::UP, '-2000', '-200', '-20', '-2'],
            [RoundingMode::UP, '-2001', '-201', '-21', '-3'],
            [RoundingMode::UP, '-2499', '-250', '-25', '-3'],
            [RoundingMode::UP, '-2500', '-250', '-25', '-3'],
            [RoundingMode::UP, '-2501', '-251', '-26', '-3'],
            [RoundingMode::UP, '-2999', '-300', '-30', '-3'],
            [RoundingMode::UP, '-3000', '-300', '-30', '-3'],
            [RoundingMode::UP, '-3001', '-301', '-31', '-4'],
            [RoundingMode::UP, '-3499', '-350', '-35', '-4'],
            [RoundingMode::UP, '-3500', '-350', '-35', '-4'],
            [RoundingMode::UP, '-3501', '-351', '-36', '-4'],

            [RoundingMode::DOWN,  '3501',  '350',  '35',  '3'],
            [RoundingMode::DOWN,  '3500',  '350',  '35',  '3'],
            [RoundingMode::DOWN,  '3499',  '349',  '34',  '3'],
            [RoundingMode::DOWN,  '3001',  '300',  '30',  '3'],
            [RoundingMode::DOWN,  '3000',  '300',  '30',  '3'],
            [RoundingMode::DOWN,  '2999',  '299',  '29',  '2'],
            [RoundingMode::DOWN,  '2501',  '250',  '25',  '2'],
            [RoundingMode::DOWN,  '2500',  '250',  '25',  '2'],
            [RoundingMode::DOWN,  '2499',  '249',  '24',  '2'],
            [RoundingMode::DOWN,  '2001',  '200',  '20',  '2'],
            [RoundingMode::DOWN,  '2000',  '200',  '20',  '2'],
            [RoundingMode::DOWN,  '1999',  '199',  '19',  '1'],
            [RoundingMode::DOWN,  '1501',  '150',  '15',  '1'],
            [RoundingMode::DOWN,  '1500',  '150',  '15',  '1'],
            [RoundingMode::DOWN,  '1499',  '149',  '14',  '1'],
            [RoundingMode::DOWN,  '1001',  '100',  '10',  '1'],
            [RoundingMode::DOWN,  '1000',  '100',  '10',  '1'],
            [RoundingMode::DOWN,   '999',   '99',   '9',  '0'],
            [RoundingMode::DOWN,   '501',   '50',   '5',  '0'],
            [RoundingMode::DOWN,   '500',   '50',   '5',  '0'],
            [RoundingMode::DOWN,   '499',   '49',   '4',  '0'],
            [RoundingMode::DOWN,     '1',    '0',   '0',  '0'],
            [RoundingMode::DOWN,     '0',    '0',   '0',  '0'],
            [RoundingMode::DOWN,    '-1',    '0',   '0',  '0'],
            [RoundingMode::DOWN,  '-499',  '-49',  '-4',  '0'],
            [RoundingMode::DOWN,  '-500',  '-50',  '-5',  '0'],
            [RoundingMode::DOWN,  '-501',  '-50',  '-5',  '0'],
            [RoundingMode::DOWN,  '-999',  '-99',  '-9',  '0'],
            [RoundingMode::DOWN, '-1000', '-100', '-10', '-1'],
            [RoundingMode::DOWN, '-1001', '-100', '-10', '-1'],
            [RoundingMode::DOWN, '-1499', '-149', '-14', '-1'],
            [RoundingMode::DOWN, '-1500', '-150', '-15', '-1'],
            [RoundingMode::DOWN, '-1501', '-150', '-15', '-1'],
            [RoundingMode::DOWN, '-1999', '-199', '-19', '-1'],
            [RoundingMode::DOWN, '-2000', '-200', '-20', '-2'],
            [RoundingMode::DOWN, '-2001', '-200', '-20', '-2'],
            [RoundingMode::DOWN, '-2499', '-249', '-24', '-2'],
            [RoundingMode::DOWN, '-2500', '-250', '-25', '-2'],
            [RoundingMode::DOWN, '-2501', '-250', '-25', '-2'],
            [RoundingMode::DOWN, '-2999', '-299', '-29', '-2'],
            [RoundingMode::DOWN, '-3000', '-300', '-30', '-3'],
            [RoundingMode::DOWN, '-3001', '-300', '-30', '-3'],
            [RoundingMode::DOWN, '-3499', '-349', '-34', '-3'],
            [RoundingMode::DOWN, '-3500', '-350', '-35', '-3'],
            [RoundingMode::DOWN, '-3501', '-350', '-35', '-3'],

            [RoundingMode::CEILING,  '3501',  '351',  '36',  '4'],
            [RoundingMode::CEILING,  '3500',  '350',  '35',  '4'],
            [RoundingMode::CEILING,  '3499',  '350',  '35',  '4'],
            [RoundingMode::CEILING,  '3001',  '301',  '31',  '4'],
            [RoundingMode::CEILING,  '3000',  '300',  '30',  '3'],
            [RoundingMode::CEILING,  '2999',  '300',  '30',  '3'],
            [RoundingMode::CEILING,  '2501',  '251',  '26',  '3'],
            [RoundingMode::CEILING,  '2500',  '250',  '25',  '3'],
            [RoundingMode::CEILING,  '2499',  '250',  '25',  '3'],
            [RoundingMode::CEILING,  '2001',  '201',  '21',  '3'],
            [RoundingMode::CEILING,  '2000',  '200',  '20',  '2'],
            [RoundingMode::CEILING,  '1999',  '200',  '20',  '2'],
            [RoundingMode::CEILING,  '1501',  '151',  '16',  '2'],
            [RoundingMode::CEILING,  '1500',  '150',  '15',  '2'],
            [RoundingMode::CEILING,  '1499',  '150',  '15',  '2'],
            [RoundingMode::CEILING,  '1001',  '101',  '11',  '2'],
            [RoundingMode::CEILING,  '1000',  '100',  '10',  '1'],
            [RoundingMode::CEILING,   '999',  '100',  '10',  '1'],
            [RoundingMode::CEILING,   '501',   '51',   '6',  '1'],
            [RoundingMode::CEILING,   '500',   '50',   '5',  '1'],
            [RoundingMode::CEILING,   '499',   '50',   '5',  '1'],
            [RoundingMode::CEILING,     '1',    '1',   '1',  '1'],
            [RoundingMode::CEILING,     '0',    '0',   '0',  '0'],
            [RoundingMode::CEILING,    '-1',    '0',   '0',  '0'],
            [RoundingMode::CEILING,  '-499',  '-49' , '-4',  '0'],
            [RoundingMode::CEILING,  '-500',  '-50' , '-5',  '0'],
            [RoundingMode::CEILING,  '-501',  '-50',  '-5',  '0'],
            [RoundingMode::CEILING,  '-999',  '-99',  '-9',  '0'],
            [RoundingMode::CEILING, '-1000', '-100', '-10', '-1'],
            [RoundingMode::CEILING, '-1001', '-100', '-10', '-1'],
            [RoundingMode::CEILING, '-1499', '-149', '-14', '-1'],
            [RoundingMode::CEILING, '-1500', '-150', '-15', '-1'],
            [RoundingMode::CEILING, '-1501', '-150', '-15', '-1'],
            [RoundingMode::CEILING, '-1999', '-199', '-19', '-1'],
            [RoundingMode::CEILING, '-2000', '-200', '-20', '-2'],
            [RoundingMode::CEILING, '-2001', '-200', '-20', '-2'],
            [RoundingMode::CEILING, '-2499', '-249', '-24', '-2'],
            [RoundingMode::CEILING, '-2500', '-250', '-25', '-2'],
            [RoundingMode::CEILING, '-2501', '-250', '-25', '-2'],
            [RoundingMode::CEILING, '-2999', '-299', '-29', '-2'],
            [RoundingMode::CEILING, '-3000', '-300', '-30', '-3'],
            [RoundingMode::CEILING, '-3001', '-300', '-30', '-3'],
            [RoundingMode::CEILING, '-3499', '-349', '-34', '-3'],
            [RoundingMode::CEILING, '-3500', '-350', '-35', '-3'],
            [RoundingMode::CEILING, '-3501', '-350', '-35', '-3'],

            [RoundingMode::FLOOR,  '3501',  '350',  '35',  '3'],
            [RoundingMode::FLOOR,  '3500',  '350',  '35',  '3'],
            [RoundingMode::FLOOR,  '3499',  '349',  '34',  '3'],
            [RoundingMode::FLOOR,  '3001',  '300',  '30',  '3'],
            [RoundingMode::FLOOR,  '3000',  '300',  '30',  '3'],
            [RoundingMode::FLOOR,  '2999',  '299',  '29',  '2'],
            [RoundingMode::FLOOR,  '2501',  '250',  '25',  '2'],
            [RoundingMode::FLOOR,  '2500',  '250',  '25',  '2'],
            [RoundingMode::FLOOR,  '2499',  '249',  '24',  '2'],
            [RoundingMode::FLOOR,  '2001',  '200',  '20',  '2'],
            [RoundingMode::FLOOR,  '2000',  '200',  '20',  '2'],
            [RoundingMode::FLOOR,  '1999',  '199',  '19',  '1'],
            [RoundingMode::FLOOR,  '1501',  '150',  '15',  '1'],
            [RoundingMode::FLOOR,  '1500',  '150',  '15',  '1'],
            [RoundingMode::FLOOR,  '1499',  '149',  '14',  '1'],
            [RoundingMode::FLOOR,  '1001',  '100',  '10',  '1'],
            [RoundingMode::FLOOR,  '1000',  '100',  '10',  '1'],
            [RoundingMode::FLOOR,   '999',   '99',   '9',  '0'],
            [RoundingMode::FLOOR,   '501',   '50',   '5',  '0'],
            [RoundingMode::FLOOR,   '500',   '50',   '5',  '0'],
            [RoundingMode::FLOOR,   '499',   '49',   '4',  '0'],
            [RoundingMode::FLOOR,     '1',    '0',   '0',  '0'],
            [RoundingMode::FLOOR,     '0',    '0',   '0',  '0'],
            [RoundingMode::FLOOR,    '-1',   '-1',  '-1', '-1'],
            [RoundingMode::FLOOR,  '-499',  '-50',  '-5', '-1'],
            [RoundingMode::FLOOR,  '-500',  '-50',  '-5', '-1'],
            [RoundingMode::FLOOR,  '-501',  '-51',  '-6', '-1'],
            [RoundingMode::FLOOR,  '-999', '-100', '-10', '-1'],
            [RoundingMode::FLOOR, '-1000', '-100', '-10', '-1'],
            [RoundingMode::FLOOR, '-1001', '-101', '-11', '-2'],
            [RoundingMode::FLOOR, '-1499', '-150', '-15', '-2'],
            [RoundingMode::FLOOR, '-1500', '-150', '-15', '-2'],
            [RoundingMode::FLOOR, '-1501', '-151', '-16', '-2'],
            [RoundingMode::FLOOR, '-1999', '-200', '-20', '-2'],
            [RoundingMode::FLOOR, '-2000', '-200', '-20', '-2'],
            [RoundingMode::FLOOR, '-2001', '-201', '-21', '-3'],
            [RoundingMode::FLOOR, '-2499', '-250', '-25', '-3'],
            [RoundingMode::FLOOR, '-2500', '-250', '-25', '-3'],
            [RoundingMode::FLOOR, '-2501', '-251', '-26', '-3'],
            [RoundingMode::FLOOR, '-2999', '-300', '-30', '-3'],
            [RoundingMode::FLOOR, '-3000', '-300', '-30', '-3'],
            [RoundingMode::FLOOR, '-3001', '-301', '-31', '-4'],
            [RoundingMode::FLOOR, '-3499', '-350', '-35', '-4'],
            [RoundingMode::FLOOR, '-3500', '-350', '-35', '-4'],
            [RoundingMode::FLOOR, '-3501', '-351', '-36', '-4'],

            [RoundingMode::HALF_UP,  '3501',  '350',  '35',  '4'],
            [RoundingMode::HALF_UP,  '3500',  '350',  '35',  '4'],
            [RoundingMode::HALF_UP,  '3499',  '350',  '35',  '3'],
            [RoundingMode::HALF_UP,  '3001',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '3000',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '2999',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '2501',  '250',  '25',  '3'],
            [RoundingMode::HALF_UP,  '2500',  '250',  '25',  '3'],
            [RoundingMode::HALF_UP,  '2499',  '250',  '25',  '2'],
            [RoundingMode::HALF_UP,  '2001',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '2000',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '1999',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '1501',  '150',  '15',  '2'],
            [RoundingMode::HALF_UP,  '1500',  '150',  '15',  '2'],
            [RoundingMode::HALF_UP,  '1499',  '150',  '15',  '1'],
            [RoundingMode::HALF_UP,  '1001',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,  '1000',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,   '999',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,   '501',   '50',   '5',  '1'],
            [RoundingMode::HALF_UP,   '500',   '50',   '5',  '1'],
            [RoundingMode::HALF_UP,   '499',   '50',   '5',  '0'],
            [RoundingMode::HALF_UP,     '1',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP,     '0',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP,    '-1',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP,  '-499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_UP,  '-500',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_UP,  '-501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_UP,  '-999', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1000', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1001', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1499', '-150', '-15', '-1'],
            [RoundingMode::HALF_UP, '-1500', '-150', '-15', '-2'],
            [RoundingMode::HALF_UP, '-1501', '-150', '-15', '-2'],
            [RoundingMode::HALF_UP, '-1999', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2000', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2001', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2499', '-250', '-25', '-2'],
            [RoundingMode::HALF_UP, '-2500', '-250', '-25', '-3'],
            [RoundingMode::HALF_UP, '-2501', '-250', '-25', '-3'],
            [RoundingMode::HALF_UP, '-2999', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3000', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3001', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3499', '-350', '-35', '-3'],
            [RoundingMode::HALF_UP, '-3500', '-350', '-35', '-4'],
            [RoundingMode::HALF_UP, '-3501', '-350', '-35', '-4'],

            [RoundingMode::HALF_DOWN,  '3501',  '350',  '35',  '4'],
            [RoundingMode::HALF_DOWN,  '3500',  '350',  '35',  '3'],
            [RoundingMode::HALF_DOWN,  '3499',  '350',  '35',  '3'],
            [RoundingMode::HALF_DOWN,  '3001',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '3000',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '2999',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '2501',  '250',  '25',  '3'],
            [RoundingMode::HALF_DOWN,  '2500',  '250',  '25',  '2'],
            [RoundingMode::HALF_DOWN,  '2499',  '250',  '25',  '2'],
            [RoundingMode::HALF_DOWN,  '2001',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '2000',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '1999',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '1501',  '150',  '15',  '2'],
            [RoundingMode::HALF_DOWN,  '1500',  '150',  '15',  '1'],
            [RoundingMode::HALF_DOWN,  '1499',  '150',  '15',  '1'],
            [RoundingMode::HALF_DOWN,  '1001',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,  '1000',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,   '999',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,   '501',   '50',   '5',  '1'],
            [RoundingMode::HALF_DOWN,   '500',   '50',   '5',  '0'],
            [RoundingMode::HALF_DOWN,   '499',   '50',   '5',  '0'],
            [RoundingMode::HALF_DOWN,     '1',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN,     '0',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN,    '-1',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN,  '-499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_DOWN,  '-500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_DOWN,  '-501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_DOWN,  '-999', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1000', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1001', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1499', '-150', '-15', '-1'],
            [RoundingMode::HALF_DOWN, '-1500', '-150', '-15', '-1'],
            [RoundingMode::HALF_DOWN, '-1501', '-150', '-15', '-2'],
            [RoundingMode::HALF_DOWN, '-1999', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2000', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2001', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2499', '-250', '-25', '-2'],
            [RoundingMode::HALF_DOWN, '-2500', '-250', '-25', '-2'],
            [RoundingMode::HALF_DOWN, '-2501', '-250', '-25', '-3'],
            [RoundingMode::HALF_DOWN, '-2999', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3000', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3001', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3499', '-350', '-35', '-3'],
            [RoundingMode::HALF_DOWN, '-3500', '-350', '-35', '-3'],
            [RoundingMode::HALF_DOWN, '-3501', '-350', '-35', '-4'],

            [RoundingMode::HALF_CEILING,  '3501',  '350',  '35',  '4'],
            [RoundingMode::HALF_CEILING,  '3500',  '350',  '35',  '4'],
            [RoundingMode::HALF_CEILING,  '3499',  '350',  '35',  '3'],
            [RoundingMode::HALF_CEILING,  '3001',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '3000',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '2999',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '2501',  '250',  '25',  '3'],
            [RoundingMode::HALF_CEILING,  '2500',  '250',  '25',  '3'],
            [RoundingMode::HALF_CEILING,  '2499',  '250',  '25',  '2'],
            [RoundingMode::HALF_CEILING,  '2001',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '2000',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '1999',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '1501',  '150',  '15',  '2'],
            [RoundingMode::HALF_CEILING,  '1500',  '150',  '15',  '2'],
            [RoundingMode::HALF_CEILING,  '1499',  '150',  '15',  '1'],
            [RoundingMode::HALF_CEILING,  '1001',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,  '1000',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,   '999',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,   '501',   '50',   '5',  '1'],
            [RoundingMode::HALF_CEILING,   '500',   '50',   '5',  '1'],
            [RoundingMode::HALF_CEILING,   '499',   '50',   '5',  '0'],
            [RoundingMode::HALF_CEILING,     '1',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING,     '0',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING,    '-1',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING,  '-499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_CEILING,  '-500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_CEILING,  '-501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_CEILING,  '-999', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1000', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1001', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1499', '-150', '-15', '-1'],
            [RoundingMode::HALF_CEILING, '-1500', '-150', '-15', '-1'],
            [RoundingMode::HALF_CEILING, '-1501', '-150', '-15', '-2'],
            [RoundingMode::HALF_CEILING, '-1999', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2000', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2001', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2499', '-250', '-25', '-2'],
            [RoundingMode::HALF_CEILING, '-2500', '-250', '-25', '-2'],
            [RoundingMode::HALF_CEILING, '-2501', '-250', '-25', '-3'],
            [RoundingMode::HALF_CEILING, '-2999', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3000', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3001', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3499', '-350', '-35', '-3'],
            [RoundingMode::HALF_CEILING, '-3500', '-350', '-35', '-3'],
            [RoundingMode::HALF_CEILING, '-3501', '-350', '-35', '-4'],

            [RoundingMode::HALF_FLOOR,  '3501',  '350',  '35',  '4'],
            [RoundingMode::HALF_FLOOR,  '3500',  '350',  '35',  '3'],
            [RoundingMode::HALF_FLOOR,  '3499',  '350',  '35',  '3'],
            [RoundingMode::HALF_FLOOR,  '3001',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '3000',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '2999',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '2501',  '250',  '25',  '3'],
            [RoundingMode::HALF_FLOOR,  '2500',  '250',  '25',  '2'],
            [RoundingMode::HALF_FLOOR,  '2499',  '250',  '25',  '2'],
            [RoundingMode::HALF_FLOOR,  '2001',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '2000',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '1999',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '1501',  '150',  '15',  '2'],
            [RoundingMode::HALF_FLOOR,  '1500',  '150',  '15',  '1'],
            [RoundingMode::HALF_FLOOR,  '1499',  '150',  '15',  '1'],
            [RoundingMode::HALF_FLOOR,  '1001',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,  '1000',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,   '999',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,   '501',   '50',   '5',  '1'],
            [RoundingMode::HALF_FLOOR,   '500',   '50',   '5',  '0'],
            [RoundingMode::HALF_FLOOR,   '499',   '50',   '5',  '0'],
            [RoundingMode::HALF_FLOOR,     '1',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR,     '0',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR,    '-1',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR,  '-499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_FLOOR,  '-500',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_FLOOR,  '-501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_FLOOR,  '-999', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1000', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1001', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1499', '-150', '-15', '-1'],
            [RoundingMode::HALF_FLOOR, '-1500', '-150', '-15', '-2'],
            [RoundingMode::HALF_FLOOR, '-1501', '-150', '-15', '-2'],
            [RoundingMode::HALF_FLOOR, '-1999', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2000', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2001', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2499', '-250', '-25', '-2'],
            [RoundingMode::HALF_FLOOR, '-2500', '-250', '-25', '-3'],
            [RoundingMode::HALF_FLOOR, '-2501', '-250', '-25', '-3'],
            [RoundingMode::HALF_FLOOR, '-2999', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3000', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3001', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3499', '-350', '-35', '-3'],
            [RoundingMode::HALF_FLOOR, '-3500', '-350', '-35', '-4'],
            [RoundingMode::HALF_FLOOR, '-3501', '-350', '-35', '-4'],

            [RoundingMode::HALF_EVEN,  '3501',  '350',  '35',  '4'],
            [RoundingMode::HALF_EVEN,  '3500',  '350',  '35',  '4'],
            [RoundingMode::HALF_EVEN,  '3499',  '350',  '35',  '3'],
            [RoundingMode::HALF_EVEN,  '3001',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '3000',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '2999',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '2501',  '250',  '25',  '3'],
            [RoundingMode::HALF_EVEN,  '2500',  '250',  '25',  '2'],
            [RoundingMode::HALF_EVEN,  '2499',  '250',  '25',  '2'],
            [RoundingMode::HALF_EVEN,  '2001',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '2000',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '1999',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '1501',  '150',  '15',  '2'],
            [RoundingMode::HALF_EVEN,  '1500',  '150',  '15',  '2'],
            [RoundingMode::HALF_EVEN,  '1499',  '150',  '15',  '1'],
            [RoundingMode::HALF_EVEN,  '1001',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,  '1000',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,   '999',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,   '501',   '50',   '5',  '1'],
            [RoundingMode::HALF_EVEN,   '500',   '50',   '5',  '0'],
            [RoundingMode::HALF_EVEN,   '499',   '50',   '5',  '0'],
            [RoundingMode::HALF_EVEN,     '1',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN,     '0',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN,    '-1',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN,  '-499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_EVEN,  '-500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_EVEN,  '-501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_EVEN,  '-999', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1000', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1001', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1499', '-150', '-15', '-1'],
            [RoundingMode::HALF_EVEN, '-1500', '-150', '-15', '-2'],
            [RoundingMode::HALF_EVEN, '-1501', '-150', '-15', '-2'],
            [RoundingMode::HALF_EVEN, '-1999', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2000', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2001', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2499', '-250', '-25', '-2'],
            [RoundingMode::HALF_EVEN, '-2500', '-250', '-25', '-2'],
            [RoundingMode::HALF_EVEN, '-2501', '-250', '-25', '-3'],
            [RoundingMode::HALF_EVEN, '-2999', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3000', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3001', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3499', '-350', '-35', '-3'],
            [RoundingMode::HALF_EVEN, '-3500', '-350', '-35', '-4'],
            [RoundingMode::HALF_EVEN, '-3501', '-350', '-35', '-4'],

            [RoundingMode::UNNECESSARY,  '3501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3500',  '350',  '35', null],
            [RoundingMode::UNNECESSARY,  '3499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3000',  '300',  '30',  '3'],
            [RoundingMode::UNNECESSARY,  '2999',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2500',  '250',  '25', null],
            [RoundingMode::UNNECESSARY,  '2499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2000',  '200',  '20',  '2'],
            [RoundingMode::UNNECESSARY,  '1999',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1500',  '150',  '15', null],
            [RoundingMode::UNNECESSARY,  '1499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1000',  '100',  '10',  '1'],
            [RoundingMode::UNNECESSARY,   '999',   null,  null, null],
            [RoundingMode::UNNECESSARY,   '501',   null,  null, null],
            [RoundingMode::UNNECESSARY,   '500',   '50',   '5', null],
            [RoundingMode::UNNECESSARY,   '499',   null,  null, null],
            [RoundingMode::UNNECESSARY,     '1',   null,  null, null],
            [RoundingMode::UNNECESSARY,     '0',    '0',   '0',  '0'],
            [RoundingMode::UNNECESSARY,    '-1',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '-499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '-500',  '-50',  '-5', null],
            [RoundingMode::UNNECESSARY,  '-501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '-999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1000', '-100', '-10', '-1'],
            [RoundingMode::UNNECESSARY, '-1001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1500', '-150', '-15', null],
            [RoundingMode::UNNECESSARY, '-1501',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2000', '-200', '-20', '-2'],
            [RoundingMode::UNNECESSARY, '-2001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2500', '-250', '-25', null],
            [RoundingMode::UNNECESSARY, '-2501',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3000', '-300', '-30', '-3'],
            [RoundingMode::UNNECESSARY, '-3001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3500', '-350', '-35', null],
            [RoundingMode::UNNECESSARY, '-3501',   null,  null, null],
        ];
    }

    /**
     * @dataProvider providerDivideAndRemainder
     *
     * @param string $dividend  The dividend.
     * @param string $divisor   The divisor.
     * @param string $quotient  The expected quotient.
     * @param string $remainder The expected remainder.
     */
    public function testDivideAndRemainder($dividend, $divisor, $quotient, $remainder)
    {
        list ($q, $r) = BigInteger::of($dividend)->divideAndRemainder($divisor);

        $this->assertSame($quotient, (string) $q);
        $this->assertSame($remainder, (string) $r);
    }

    /**
     * @return array
     */
    public function providerDivideAndRemainder()
    {
        return [
            ['1', '123', '0', '1'],
            ['1', '-123', '0', '1'],
            ['-1', '123', '0', '-1'],
            ['-1', '-123', '0', '-1'],

            ['123', '1', '123', '0'],
            ['123', '-1', '-123', '0'],
            ['-123', '1', '-123', '0'],
            ['-123', '-1', '123', '0'],

            ['123', '2', '61', '1'],
            ['123', '-2', '-61', '1'],
            ['-123', '2', '-61', '-1'],
            ['-123', '-2', '61', '-1'],

            ['123', '123', '1', '0'],
            ['123', '-123', '-1', '0'],
            ['-123', '123', '-1', '0'],
            ['-123', '-123', '1', '0'],

            ['123', '124', '0', '123'],
            ['123', '-124', '0', '123'],
            ['-123', '124', '0', '-123'],
            ['-123', '-124', '0', '-123'],

            ['124', '123', '1', '1'],
            ['124', '-123', '-1', '1'],
            ['-124', '123', '-1', '-1'],
            ['-124', '-123', '1', '-1'],

            ['1000000000000000000000000000000', '3', '333333333333333333333333333333', '1'],
            ['1000000000000000000000000000000', '9', '111111111111111111111111111111', '1'],
            ['1000000000000000000000000000000', '11', '90909090909090909090909090909', '1'],
            ['1000000000000000000000000000000', '13', '76923076923076923076923076923', '1'],
            ['1000000000000000000000000000000', '21', '47619047619047619047619047619', '1'],

            ['123456789123456789123456789', '987654321987654321', '124999998', '850308642973765431'],
            ['123456789123456789123456789', '-87654321987654321', '-1408450676', '65623397056685793'],
            ['-123456789123456789123456789', '7654321987654321', '-16129030020', '-1834176331740369'],
            ['-123456789123456789123456789', '-654321987654321', '188678955396', '-205094497790673'],
        ];
    }

    /**
     * @dataProvider providerAbs
     *
     * @param string $number   The number as a string.
     * @param string $expected The expected absolute result.
     */
    public function testAbs($number, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigInteger::of($number)->abs());
    }

    /**
     * @return array
     */
    public function providerAbs()
    {
        return [
            ['0', '0'],
            ['123456789012345678901234567890', '123456789012345678901234567890'],
            ['-123456789012345678901234567890', '123456789012345678901234567890'],
        ];
    }

    /**
     * @dataProvider providerNegated
     *
     * @param string $number   The number to negate as a string.
     * @param string $expected The expected negated result.
     */
    public function testNegated($number, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigInteger::of($number)->negated());
    }

    /**
     * @return array
     */
    public function providerNegated()
    {
        return [
            ['0', '0'],
            ['123456789012345678901234567890', '-123456789012345678901234567890'],
            ['-123456789012345678901234567890', '123456789012345678901234567890'],
        ];
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The expected comparison result.
     */
    public function testCompareTo($a, $b, $c)
    {
        $this->assertSame($c, BigInteger::of($a)->compareTo($b));
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The comparison result.
     */
    public function testIsEqualTo($a, $b, $c)
    {
        $this->assertSame($c == 0, BigInteger::of($a)->isEqualTo($b));
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The comparison result.
     */
    public function testIsLessThan($a, $b, $c)
    {
        $this->assertSame($c < 0, BigInteger::of($a)->isLessThan($b));
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The comparison result.
     */
    public function testIsLessThanOrEqualTo($a, $b, $c)
    {
        $this->assertSame($c <= 0, BigInteger::of($a)->isLessThanOrEqualTo($b));
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The comparison result.
     */
    public function testIsGreaterThan($a, $b, $c)
    {
        $this->assertSame($c > 0, BigInteger::of($a)->isGreaterThan($b));
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param string  $a The base number as a string.
     * @param string  $b The number to compare to as a string.
     * @param integer $c The comparison result.
     */
    public function testIsGreaterThanOrEqualTo($a, $b, $c)
    {
        $this->assertSame($c >= 0, BigInteger::of($a)->isGreaterThanOrEqualTo($b));
    }

    /**
     * @return array
     */
    public function providerCompareTo()
    {
        return [
            ['123', '123',  0],
            ['123', '456', -1],
            ['456', '123',  1],
            ['456', '456',  0],

            ['-123', '-123',  0],
            ['-123',  '456', -1],
            [ '456', '-123',  1],
            [ '456',  '456',  0],

            [ '123',  '123',  0],
            [ '123', '-456',  1],
            ['-456',  '123', -1],
            ['-456',  '456', -1],

            ['-123', '-123',  0],
            ['-123', '-456',  1],
            ['-456', '-123', -1],
            ['-456', '-456',  0],

            [ '9999999999999999999999999',  '11111111111111111111111111111111111111111111', -1],
            [ '9999999999999999999999999', '-11111111111111111111111111111111111111111111',  1],
            ['-9999999999999999999999999',  '11111111111111111111111111111111111111111111', -1],
            ['-9999999999999999999999999', '-11111111111111111111111111111111111111111111',  1],

            [ '11111111111111111111111111111111111111111111', '9999999999999999999999999',  1],
            [ '11111111111111111111111111111111111111111111', '-9999999999999999999999999', 1],
            ['-11111111111111111111111111111111111111111111', '9999999999999999999999999', -1],
            ['-11111111111111111111111111111111111111111111','-9999999999999999999999999', -1],
        ];
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|integer $number The number to test.
     * @param boolean        $cmp    The comparison value.
     */
    public function testIsZero($number, $cmp)
    {
        $this->assertSame($cmp == 0, BigInteger::of($number)->isZero());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|integer $number The number to test.
     * @param boolean        $cmp    The comparison value.
     */
    public function testIsNegative($number, $cmp)
    {
        $this->assertSame($cmp < 0, BigInteger::of($number)->isNegative());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|integer $number The number to test.
     * @param boolean        $cmp    The comparison value.
     */
    public function testIsNegativeOrZero($number, $cmp)
    {
        $this->assertSame($cmp <= 0, BigInteger::of($number)->isNegativeOrZero());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|integer $number The number to test.
     * @param boolean        $cmp    The comparison value.
     */
    public function testIsPositive($number, $cmp)
    {
        $this->assertSame($cmp > 0, BigInteger::of($number)->isPositive());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|integer $number The number to test.
     * @param boolean        $cmp    The comparison value.
     */
    public function testIsPositiveOrZero($number, $cmp)
    {
        $this->assertSame($cmp >= 0, BigInteger::of($number)->isPositiveOrZero());
    }

    /**
     * @return array
     */
    public function providerCompareToZero()
    {
        return [
            [ 0,  0],
            [-0,  0],
            [ 1,  1],
            [-1, -1],

            [PHP_INT_MAX, 1],
            [~PHP_INT_MAX, -1],

            [ '1000000000000000000000000000000000000000000000000000000000000000000000000000000000', 1],
            ['-1000000000000000000000000000000000000000000000000000000000000000000000000000000000', -1]
        ];
    }

    /**
     * @dataProvider providerToInteger
     *
     * @param integer $number
     */
    public function testToInteger($number)
    {
        $this->assertSame($number, BigInteger::of((string) $number)->toInteger());
    }

    /**
     * @return array
     */
    public function providerToInteger()
    {
        return [
            [~PHP_INT_MAX],
            [-123456789],
            [-1],
            [0],
            [1],
            [123456789],
            [PHP_INT_MAX]
        ];
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testToIntegerNegativeOverflowThrowsException()
    {
        BigInteger::of(~PHP_INT_MAX)->minus(1)->toInteger();
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testToIntegerPositiveOverflowThrowsException()
    {
        BigInteger::of(PHP_INT_MAX)->plus(1)->toInteger();
    }

    /**
     * @dataProvider providerToString
     *
     * @param string  $number   The number to convert.
     * @param integer $base     The base to convert the number to.
     * @param string  $expected The expected result.
     */
    public function testToString($number, $base, $expected)
    {
        $this->assertSame($expected, BigInteger::parse($number)->toString($base));
    }

    /**
     * @return array
     */
    public function providerToString()
    {
        return [
            ['640998479760579495168036691627608949', 36, '110011001100110011001111'],
            ['335582856048758779730579523833856636', 35, '110011001100110011001111'],
            ['172426711023004493064981145981549295', 34, '110011001100110011001111'],
            ['86853227285668653965326574185738990',  33, '110011001100110011001111'],
            ['42836489934972583913564073319498785',  32, '110011001100110011001111'],
            ['20658924711984480538771889603666144',  31, '110011001100110011001111'],
            ['9728140488839986222205212599027931',   30, '110011001100110011001111'],
            ['4465579470019956787945275674107410',   29, '110011001100110011001111'],
            ['1994689924537781753408144504465645',   28, '110011001100110011001111'],
            ['865289950909412968716094193925700',    27, '110011001100110011001111'],
            ['363729369583879309352831568000039',    26, '110011001100110011001111'],
            ['147793267388865354156500488297526',    25, '110011001100110011001111'],
            ['57888012016107577099138793486425',     24, '110011001100110011001111'],
            ['21788392294523974761749372677800',     23, '110011001100110011001111'],
            ['7852874701996329566765721637715',      22, '110011001100110011001111'],
            ['2699289081943123258094476428634',      21, '110011001100110011001111'],
            ['880809345058406615041344008421',       20, '110011001100110011001111'],
            ['271401690926468032718781859340',       19, '110011001100110011001111'],
            ['78478889737009209699633503455',        18, '110011001100110011001111'],
            ['21142384915931646646976872830',        17, '110011001100110011001111'],
            ['5261325448418072742917574929',         16, '110011001100110011001111'],
            ['1197116069565850925807253616',         15, '110011001100110011001111'],
            ['245991074299834917455374155',          14, '110011001100110011001111'],
            ['44967318722190498361960610',           13, '110011001100110011001111'],
            ['7177144825886069940574045',            12, '110011001100110011001111'],
            ['976899716207148313491924',             11, '110011001100110011001111'],
            ['110011001100110011001111',             10, '110011001100110011001111'],
            ['9849210196991880028870',                9, '110011001100110011001111'],
            ['664244955832213832265',                 8, '110011001100110011001111'],
            ['31291601125492514360',                  7, '110011001100110011001111'],
            ['922063395565287619',                    6, '110011001100110011001111'],
            ['14328039609468906',                     5, '110011001100110011001111'],
            ['88305875046485',                        4, '110011001100110011001111'],
            ['127093291420',                          3, '110011001100110011001111'],
            ['13421775',                              2, '110011001100110011001111'],

            ['106300512100105327644605138221229898724869759421181854980', 36, 'zyxwvutsrqponmlkjihgfedcba9876543210'],
            ['1101553773143634726491620528194292510495517905608180485',   35,  'yxwvutsrqponmlkjihgfedcba9876543210'],
            ['11745843093701610854378775891116314824081102660800418',     34,   'xwvutsrqponmlkjihgfedcba9876543210'],
            ['128983956064237823710866404905431464703849549412368',       33,    'wvutsrqponmlkjihgfedcba9876543210'],
            ['1459980823972598128486511383358617792788444579872',         32,     'vutsrqponmlkjihgfedcba9876543210'],
            ['17050208381689099029767742314582582184093573615',           31,      'utsrqponmlkjihgfedcba9876543210'],
            ['205646315052919334126040428061831153388822830',             30,       'tsrqponmlkjihgfedcba9876543210'],
            ['2564411043271974895869785066497940850811934',               29,        'srqponmlkjihgfedcba9876543210'],
            ['33100056003358651440264672384704297711484',                 28,         'rqponmlkjihgfedcba9876543210'],
            ['442770531899482980347734468443677777577',                   27,          'qponmlkjihgfedcba9876543210'],
            ['6146269788878825859099399609538763450',                     26,           'ponmlkjihgfedcba9876543210'],
            ['88663644327703473714387251271141900',                       25,            'onmlkjihgfedcba9876543210'],
            ['1331214537196502869015340298036888',                        24,             'nmlkjihgfedcba9876543210'],
            ['20837326537038308910317109288851',                          23,              'mlkjihgfedcba9876543210'],
            ['340653664490377789692799452102',                            22,               'lkjihgfedcba9876543210'],
            ['5827980550840017565077671610',                              21,                'kjihgfedcba9876543210'],
            ['104567135734072022160664820',                               20,                 'jihgfedcba9876543210'],
            ['1972313422155189164466189',                                 19,                  'ihgfedcba9876543210'],
            ['39210261334551566857170',                                   18,                   'hgfedcba9876543210'],
            ['824008854613343261192',                                     17,                    'gfedcba9876543210'],
            ['18364758544493064720',                                      16,                     'fedcba9876543210'],
            ['435659737878916215',                                        15,                      'edcba9876543210'],
            ['11046255305880158',                                         14,                       'dcba9876543210'],
            ['300771807240918',                                           13,                        'cba9876543210'],
            ['8842413667692',                                             12,                         'ba9876543210'],
            ['282458553905',                                              11,                          'a9876543210'],
            ['9876543210',                                                10,                           '9876543210'],
            ['381367044',                                                  9,                            '876543210'],
            ['16434824',                                                   8,                             '76543210'],
            ['800667',                                                     7,                              '6543210'],
            ['44790',                                                      6,                               '543210'],
            ['2930',                                                       5,                                '43210'],
            ['228',                                                        4,                                 '3210'],
            ['21',                                                         3,                                  '210'],
            ['2',                                                          2,                                   '10'],
        ];
    }
}
