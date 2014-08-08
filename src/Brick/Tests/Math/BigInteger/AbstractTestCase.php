<?php

namespace Brick\Tests\Math\BigInteger;

use Brick\Math\Calculator;
use Brick\Math\BigInteger;

/**
 * Unit tests for class BigInteger.
 */
abstract class AbstractTestCase extends \Brick\Tests\Math\AbstractTestCase
{
    public function testOfBigInteger()
    {
        $zero = BigInteger::zero();
        $this->assertSame($zero, BigInteger::of($zero));
    }

    public function testOfInteger()
    {
        $this->assertSame('123456789', (string) BigInteger::of(123456789));
    }

    public function testOfString()
    {
        $number = '123456789012345678901234567890123456789012345678901234567890';
        $this->assertSame($number, (string) BigInteger::of($number));
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
     * @dataProvider providerShiftedLeft
     *
     * @param string  $number   The number to shift.
     * @param integer $bits     The number of bits to shift.
     * @param string  $expected The expected result.
     */
    public function testShiftedLeft($number, $bits, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigInteger::of($number)->shiftedLeft($bits));
    }

    /**
     * @return array
     */
    public function providerShiftedLeft()
    {
        return [
            ['1',  0, '1'],
            ['1',  1, '2'],
            ['1', 63, '9223372036854775808'],
            ['1', 64, '18446744073709551616'],
            ['1', 65, '36893488147419103232'],

            ['3',   0, '3'],
            ['3',   1, '6'],
            ['3', 127, '510423550381407695195061911147652317184'],
            ['3', 128, '1020847100762815390390123822295304634368'],
            ['3', 129, '2041694201525630780780247644590609268736'],

            ['-1',  0, '-1'],
            ['-1',  1, '-2'],
            ['-1', 63, '-9223372036854775808'],
            ['-1', 64, '-18446744073709551616'],
            ['-1', 65, '-36893488147419103232'],

            ['-3',   0, '-3'],
            ['-3',   1, '-6'],
            ['-3', 127, '-510423550381407695195061911147652317184'],
            ['-3', 128, '-1020847100762815390390123822295304634368'],
            ['-3', 129, '-2041694201525630780780247644590609268736'],

            ['42', 0, '42'],
            ['42', 1, '84'],
            ['42', 2, '168'],
            ['42', 10, '43008'],
            ['42', 20, '44040192'],
            ['42', 100, '53241325209585634862861534625792'],
            ['42', 200, '67491397858877591572762407878328829305932525738877299082657792'],

            ['-42', 0, '-42'],
            ['-42', 1, '-84'],
            ['-42', 2, '-168'],
            ['-42', 10, '-43008'],
            ['-42', 20, '-44040192'],
            ['-42', 100, '-53241325209585634862861534625792'],
            ['-42', 200, '-67491397858877591572762407878328829305932525738877299082657792'],

            ['123456789123456789123456789', 0, '123456789123456789123456789'],
            ['123456789123456789123456789', 1, '246913578246913578246913578'],
            ['123456789123456789123456789', 2, '493827156493827156493827156'],
            ['123456789123456789123456789', 3, '987654312987654312987654312'],
            ['123456789123456789123456789', 10, '126419752062419752062419751936'],
            ['123456789123456789123456789', 20, '129453826111917826111917825982464'],
            ['123456789123456789123456789', 30, '132560717938603853938603853806043136'],
            ['123456789123456789123456789', 100, '156500072834599941898774713564003138549903269485728497664'],

            ['-123456789123456789123456789', 0, '-123456789123456789123456789'],
            ['-123456789123456789123456789', 1, '-246913578246913578246913578'],
            ['-123456789123456789123456789', 2, '-493827156493827156493827156'],
            ['-123456789123456789123456789', 3, '-987654312987654312987654312'],
            ['-123456789123456789123456789', 10, '-126419752062419752062419751936'],
            ['-123456789123456789123456789', 20, '-129453826111917826111917825982464'],
            ['-123456789123456789123456789', 30, '-132560717938603853938603853806043136'],
            ['-123456789123456789123456789', 100, '-156500072834599941898774713564003138549903269485728497664'],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShiftedLeftThrowsExceptionWhenBitsIsNegative()
    {
        BigInteger::of(1)->shiftedLeft(-1);
    }

    /**
     * @dataProvider providerShiftedRight
     *
     * @param string  $number   The number to shift.
     * @param integer $bits     The number of bits to shift.
     * @param string  $expected The expected result.
     */
    public function testShiftedRight($number, $bits, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigInteger::of($number)->shiftedRight($bits));
    }

    /**
     * @return array
     */
    public function providerShiftedRight()
    {
        return [
            ['1', 0, '1'],
            ['1', 1, '0'],
            ['1', 10, '0'],
            ['1', 1000, '0'],

            ['-1', 0, '-1'],
            ['-1', 1, '-1'],
            ['-1', 10, '-1'],
            ['-1', 1000, '-1'],

            ['42', 0, '42'],
            ['42', 1, '21'],
            ['42', 2, '10'],
            ['42', 3, '5'],
            ['42', 4, '2'],
            ['42', 5, '1'],
            ['42', 6, '0'],
            ['42', 7, '0'],

            ['-42', 0, '-42'],
            ['-42', 1, '-21'],
            ['-42', 2, '-11'],
            ['-42', 3, '-6'],
            ['-42', 4, '-3'],
            ['-42', 5, '-2'],
            ['-42', 6, '-1'],
            ['-42', 7, '-1'],

            ['42', 0, '42'],
            ['42', 1, '21'],
            ['42', 2, '10'],
            ['42', 3, '5'],
            ['42', 4, '2'],
            ['42', 5, '1'],
            ['42', 6, '0'],
            ['42', 7, '0'],

            ['-42', 0, '-42'],
            ['-42', 1, '-21'],
            ['-42', 2, '-11'],
            ['-42', 3, '-6'],
            ['-42', 4, '-3'],
            ['-42', 5, '-2'],
            ['-42', 6, '-1'],
            ['-42', 7, '-1'],

            ['3640', 0, '3640'],
            ['3640', 1, '1820'],
            ['3640', 2, '910'],
            ['3640', 3, '455'],
            ['3640', 4, '227'],
            ['3640', 5, '113'],
            ['3640', 6, '56'],
            ['3640', 7, '28'],
            ['3640', 8, '14'],
            ['3640', 9, '7'],
            ['3640', 10, '3'],
            ['3640', 11, '1'],
            ['3640', 12, '0'],
            ['3640', 13, '0'],

            ['-3640', 0, '-3640'],
            ['-3640', 1, '-1820'],
            ['-3640', 2, '-910'],
            ['-3640', 3, '-455'],
            ['-3640', 4, '-228'],
            ['-3640', 5, '-114'],
            ['-3640', 6, '-57'],
            ['-3640', 7, '-29'],
            ['-3640', 8, '-15'],
            ['-3640', 9, '-8'],
            ['-3640', 10, '-4'],
            ['-3640', 11, '-2'],
            ['-3640', 12, '-1'],
            ['-3640', 13, '-1'],

            ['123456789123456789123456789', 0, '123456789123456789123456789'],
            ['123456789123456789123456789', 1, '61728394561728394561728394'],
            ['123456789123456789123456789', 2, '30864197280864197280864197'],
            ['123456789123456789123456789', 3, '15432098640432098640432098'],
            ['123456789123456789123456789', 4, '7716049320216049320216049'],
            ['123456789123456789123456789', 5, '3858024660108024660108024'],
            ['123456789123456789123456789', 10, '120563270628375770628375'],
            ['123456789123456789123456789', 20, '117737568973023213504'],
            ['123456789123456789123456789', 30, '114978094700217981'],
            ['123456789123456789123456789', 40, '112283295605681'],
            ['123456789123456789123456789', 50, '109651655864'],
            ['123456789123456789123456789', 80, '102'],
            ['123456789123456789123456789', 81, '51'],
            ['123456789123456789123456789', 82, '25'],
            ['123456789123456789123456789', 83, '12'],
            ['123456789123456789123456789', 84, '6'],
            ['123456789123456789123456789', 85, '3'],
            ['123456789123456789123456789', 86, '1'],
            ['123456789123456789123456789', 87, '0'],
            ['123456789123456789123456789', 88, '0'],

            ['-123456789123456789123456789', 0, '-123456789123456789123456789'],
            ['-123456789123456789123456789', 1, '-61728394561728394561728395'],
            ['-123456789123456789123456789', 2, '-30864197280864197280864198'],
            ['-123456789123456789123456789', 3, '-15432098640432098640432099'],
            ['-123456789123456789123456789', 4, '-7716049320216049320216050'],
            ['-123456789123456789123456789', 5, '-3858024660108024660108025'],
            ['-123456789123456789123456789', 10, '-120563270628375770628376'],
            ['-123456789123456789123456789', 20, '-117737568973023213505'],
            ['-123456789123456789123456789', 30, '-114978094700217982'],
            ['-123456789123456789123456789', 40, '-112283295605682'],
            ['-123456789123456789123456789', 50, '-109651655865'],
            ['-123456789123456789123456789', 80, '-103'],
            ['-123456789123456789123456789', 81, '-52'],
            ['-123456789123456789123456789', 82, '-26'],
            ['-123456789123456789123456789', 83, '-13'],
            ['-123456789123456789123456789', 84, '-7'],
            ['-123456789123456789123456789', 85, '-4'],
            ['-123456789123456789123456789', 86, '-2'],
            ['-123456789123456789123456789', 87, '-1'],
            ['-123456789123456789123456789', 88, '-1'],
        ];
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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShiftedRightThrowsExceptionWhenBitsIsNegative()
    {
        BigInteger::of(1)->shiftedRight(-1);
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
}
