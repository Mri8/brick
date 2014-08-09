<?php

namespace Brick\Tests\Math\BigDecimal;

use Brick\Math\ArithmeticException;
use Brick\Math\Internal\Calculator;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

/**
 * Unit tests for class BigDecimal.
 */
abstract class AbstractTestCase extends \Brick\Tests\Math\AbstractTestCase
{
    /**
     * @dataProvider providerOf
     *
     * @param string|number $value         The value to convert to a BigDecimal.
     * @param string        $unscaledValue The expected unscaled value.
     * @param integer       $scale         The expected scale.
     */
    public function testOf($value, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($value));
    }

    /**
     * @return array
     */
    public function providerOf()
    {
        return [
            [0, '0', 0],
            [1, '1', 0],
            [-1, '-1', 0],
            [123456789, '123456789', 0],
            [-123456789, '-123456789', 0],
            [PHP_INT_MAX, (string) PHP_INT_MAX, 0],
            [~PHP_INT_MAX, (string) ~PHP_INT_MAX, 0],

            [0.0, '0', 0],
            [0.1, '1', 1],
            [1.0, '1', 0],
            [1.1, '11', 1],

            ['0', '0', 0],
            ['+0', '0', 0],
            ['-0', '0', 0],
            ['00', '0', 0],
            ['+00', '0', 0],
            ['-00', '0', 0],

            ['1', '1', 0],
            ['+1', '1', 0],
            ['-1', '-1', 0],
            ['01', '1', 0],
            ['+01', '1', 0],
            ['-01', '-1', 0],

            ['0.0', '0', 1],
            ['+0.0', '0', 1],
            ['-0.0', '0', 1],
            ['00.0', '0', 1],
            ['+00.0', '0', 1],
            ['-00.0', '0', 1],

            ['1.0', '10', 1],
            ['+1.0', '10', 1],
            ['-1.0', '-10', 1],
            ['01.0', '10', 1],
            ['+01.0', '10', 1],
            ['-01.0', '-10', 1],

            ['0.1', '1', 1],
            ['+0.1', '1', 1],
            ['-0.1', '-1', 1],
            ['0.10', '10', 2],
            ['+0.10', '10', 2],
            ['-0.10', '-10', 2],
            ['0.010', '10', 3],
            ['+0.010', '10', 3],
            ['-0.010', '-10', 3],

            ['00.1', '1', 1],
            ['+00.1', '1', 1],
            ['-00.1', '-1', 1],
            ['00.10', '10', 2],
            ['+00.10', '10', 2],
            ['-00.10', '-10', 2],
            ['00.010', '10', 3],
            ['+00.010', '10', 3],
            ['-00.010', '-10', 3],

            ['01.1', '11', 1],
            ['+01.1', '11', 1],
            ['-01.1', '-11', 1],
            ['01.010', '1010', 3],
            ['+01.010', '1010', 3],
            ['-01.010', '-1010', 3],

            ['0e-2', '0', 2],
            ['0e-1', '0', 1],
            ['0e-0', '0', 0],
            ['0e0', '0', 0],
            ['0e1', '0', 0],
            ['0e2', '0', 0],
            ['0e+0', '0', 0],
            ['0e+1','0', 0],
            ['0e+2','0', 0],

            ['0.0e-2', '0', 3],
            ['0.0e-1', '0', 2],
            ['0.0e-0', '0', 1],
            ['0.0e0', '0', 1],
            ['0.0e1', '0', 0],
            ['0.0e2', '0', 0],
            ['0.0e+0', '0', 1],
            ['0.0e+1','0', 0],
            ['0.0e+2','0', 0],

            ['0.1e-2', '1', 3],
            ['0.1e-1', '1', 2],
            ['0.1e-0', '1', 1],
            ['0.1e0', '1', 1],
            ['0.1e1', '1', 0],
            ['0.1e2', '10', 0],
            ['0.1e+0', '1', 1],
            ['0.1e+1','1', 0],
            ['0.1e+2','10', 0],

            ['0.01e-2', '1', 4],
            ['0.01e-1', '1', 3],
            ['0.01e-0', '1', 2],
            ['0.01e0', '1', 2],
            ['0.01e1', '1', 1],
            ['0.01e2', '1', 0],
            ['0.01e+0', '1', 2],
            ['0.01e+1','1', 1],
            ['0.01e+2','1', 0],

            ['0.10e-2', '10', 4],
            ['0.10e-1', '10', 3],
            ['0.10e-0', '10', 2],
            ['0.10e0', '10', 2],
            ['0.10e1', '10', 1],
            ['0.10e2', '10', 0],
            ['0.10e+0', '10', 2],
            ['0.10e+1','10', 1],
            ['0.10e+2','10', 0],

            ['00.10e-2', '10', 4],
            ['+00.10e-1', '10', 3],
            ['-00.10e-0', '-10', 2],
            ['00.10e0', '10', 2],
            ['+00.10e1', '10', 1],
            ['-00.10e2', '-10', 0],
            ['00.10e+0', '10', 2],
            ['+00.10e+1','10', 1],
            ['-00.10e+2','-10', 0],
        ];
    }

    public function testOfBigDecimalReturnsThis()
    {
        $decimal = BigDecimal::of(123);

        $this->assertSame($decimal, BigDecimal::of($decimal));
    }

    /**
     * @dataProvider providerOfUnscaledValue
     *
     * @param string  $unscaledValue         The unscaled value of the BigDecimal to create.
     * @param integer $scale                 The scale of the BigDecimal to create.
     * @param string  $expectedUnscaledValue The expected result unscaled value.
     */
    public function testOfUnscaledValue($unscaledValue, $scale, $expectedUnscaledValue)
    {
        $number = BigDecimal::ofUnscaledValue($unscaledValue, $scale);
        $this->assertBigDecimalEquals($expectedUnscaledValue, $scale, $number);
    }

    /**
     * @return array
     */
    public function providerOfUnscaledValue()
    {
        return [
            ['123456789012345678901234567890', 0, '123456789012345678901234567890'],
            ['123456789012345678901234567890', 1, '123456789012345678901234567890'],
            ['+123456789012345678901234567890', 0, '123456789012345678901234567890'],
            ['+123456789012345678901234567890', 1, '123456789012345678901234567890'],
            ['-123456789012345678901234567890', 0, '-123456789012345678901234567890'],
            ['-123456789012345678901234567890', 1, '-123456789012345678901234567890'],

            ['0123456789012345678901234567890', 0, '123456789012345678901234567890'],
            ['0123456789012345678901234567890', 1, '123456789012345678901234567890'],
            ['+0123456789012345678901234567890', 0, '123456789012345678901234567890'],
            ['+0123456789012345678901234567890', 1, '123456789012345678901234567890'],
            ['-0123456789012345678901234567890', 0, '-123456789012345678901234567890'],
            ['-0123456789012345678901234567890', 1, '-123456789012345678901234567890'],
        ];
    }

    public function testZero()
    {
        $this->assertBigDecimalEquals('0', 0, BigDecimal::zero());
    }

    /**
     * @dataProvider providerPlus
     *
     * @param string  $a             The base number.
     * @param string  $b             The number to add.
     * @param string  $unscaledValue The expected unscaled value.
     * @param integer $scale         The expected scale.
     */
    public function testPlus($a, $b, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($a)->plus($b));
    }

    /**
     * @return array
     */
    public function providerPlus()
    {
        return [
            ['123',    '999',    '1122',   0],
            ['123',    '999.0',  '11220',  1],
            ['123',    '999.00', '112200', 2],
            ['123.0',  '999',    '11220',  1],
            ['123.0',  '999.0',  '11220',  1],
            ['123.0',  '999.00', '112200', 2],
            ['123.00', '999',    '112200', 2],
            ['123.00', '999.0',  '112200', 2],
            ['123.00', '999.00', '112200', 2],

            ['123',    '-999',    '-876',   0],
            ['123',    '-999.0',  '-8760',  1],
            ['123',    '-999.00', '-87600', 2],
            ['123.0',  '-999',    '-8760',  1],
            ['123.0',  '-999.0',  '-8760',  1],
            ['123.0',  '-999.00', '-87600', 2],
            ['123.00', '-999',    '-87600', 2],
            ['123.00', '-999.0',  '-87600', 2],
            ['123.00', '-999.00', '-87600', 2],

            ['-123',    '999',    '876',   0],
            ['-123',    '999.0',  '8760',  1],
            ['-123',    '999.00', '87600', 2],
            ['-123.0',  '999',    '8760',  1],
            ['-123.0',  '999.0',  '8760',  1],
            ['-123.0',  '999.00', '87600', 2],
            ['-123.00', '999',    '87600', 2],
            ['-123.00', '999.0',  '87600', 2],
            ['-123.00', '999.00', '87600', 2],

            ['-123',    '-999',    '-1122',   0],
            ['-123',    '-999.0',  '-11220',  1],
            ['-123',    '-999.00', '-112200', 2],
            ['-123.0',  '-999',    '-11220',  1],
            ['-123.0',  '-999.0',  '-11220',  1],
            ['-123.0',  '-999.00', '-112200', 2],
            ['-123.00', '-999',    '-112200', 2],
            ['-123.00', '-999.0',  '-112200', 2],
            ['-123.00', '-999.00', '-112200', 2],

            ['23487837847837428335.322387091', '309049304233535454687656.2392', '309072792071383292115991561587091', 9],
            ['-234878378478328335.322387091', '309049304233535154687656.232', '309049069355156676359320909612909', 9],
            ['234878378478328335.3227091', '-3090495154687656.231343344452', '231787883323640679091365755548', 12],
            ['-23487837847833435.3231', '-3090495154687656.231343344452', '-26578333002521091554443344452', 12]
        ];
    }

    /**
     * @dataProvider providerMinus
     *
     * @param string  $a             The base number.
     * @param string  $b             The number to subtract.
     * @param string  $unscaledValue The expected unscaled value.
     * @param integer $scale         The expected scale.
     */
    public function testMinus($a, $b, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($a)->minus($b));
    }

    /**
     * @return array
     */
    public function providerMinus()
    {
        return [
            ['123',    '999',    '-876',   0],
            ['123',    '999.0',  '-8760',  1],
            ['123',    '999.00', '-87600', 2],
            ['123.0',  '999',    '-8760',  1],
            ['123.0',  '999.0',  '-8760',  1],
            ['123.0',  '999.00', '-87600', 2],
            ['123.00', '999',    '-87600', 2],
            ['123.00', '999.0',  '-87600', 2],
            ['123.00', '999.00', '-87600', 2],

            ['123',    '-999',    '1122',   0],
            ['123',    '-999.0',  '11220',  1],
            ['123',    '-999.00', '112200', 2],
            ['123.0',  '-999',    '11220',  1],
            ['123.0',  '-999.0',  '11220',  1],
            ['123.0',  '-999.00', '112200', 2],
            ['123.00', '-999',    '112200', 2],
            ['123.00', '-999.0',  '112200', 2],
            ['123.00', '-999.00', '112200', 2],

            ['-123',    '999',    '-1122',   0],
            ['-123',    '999.0',  '-11220',  1],
            ['-123',    '999.00', '-112200', 2],
            ['-123.0',  '999',    '-11220',  1],
            ['-123.0',  '999.0',  '-11220',  1],
            ['-123.0',  '999.00', '-112200', 2],
            ['-123.00', '999',    '-112200', 2],
            ['-123.00', '999.0',  '-112200', 2],
            ['-123.00', '999.00', '-112200', 2],

            ['-123',    '-999',    '876',   0],
            ['-123',    '-999.0',  '8760',  1],
            ['-123',    '-999.00', '87600', 2],
            ['-123.0',  '-999',    '8760',  1],
            ['-123.0',  '-999.0',  '8760',  1],
            ['-123.0',  '-999.00', '87600', 2],
            ['-123.00', '-999',    '87600', 2],
            ['-123.00', '-999.0',  '87600', 2],
            ['-123.00', '-999.00', '87600', 2],

            ['234878378477428335.3223334343487091', '309049304233536.2392', '2345693291731947990831334343487091', 16],
            ['-2348783784774335.32233343434891', '309049304233536.233392', '-265783308900787155572543434891', 14],
            ['2348783784774335.323232342791', '-309049304233536.556172', '2657833089007871879404342791', 12],
            ['-2348783784774335.3232342791', '-309049304233536.556172', '-20397344805407987670622791', 10]
        ];
    }

    /**
     * @dataProvider providerMultipliedBy
     *
     * @param string  $a             The base number.
     * @param string  $b             The number to multiply.
     * @param string  $unscaledValue The expected unscaled value.
     * @param integer $scale         The expected scale.
     */
    public function testMultipliedBy($a, $b, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($a)->multipliedBy($b));
    }

    /**
     * @return array
     */
    public function providerMultipliedBy()
    {
        return [
            ['123',    '999',    '122877',     0],
            ['123',    '999.0',  '1228770',    1],
            ['123',    '999.00', '12287700',   2],
            ['123.0',  '999',    '1228770',    1],
            ['123.0',  '999.0',  '12287700',   2],
            ['123.0',  '999.00', '122877000',  3],
            ['123.00', '999',    '12287700',   2],
            ['123.00', '999.0',  '122877000',  3],
            ['123.00', '999.00', '1228770000', 4],

            ['123',    '-999',    '-122877',     0],
            ['123',    '-999.0',  '-1228770',    1],
            ['123',    '-999.00', '-12287700',   2],
            ['123.0',  '-999',    '-1228770',    1],
            ['123.0',  '-999.0',  '-12287700',   2],
            ['123.0',  '-999.00', '-122877000',  3],
            ['123.00', '-999',    '-12287700',   2],
            ['123.00', '-999.0',  '-122877000',  3],
            ['123.00', '-999.00', '-1228770000', 4],

            ['-123',    '999',    '-122877',     0],
            ['-123',    '999.0',  '-1228770',    1],
            ['-123',    '999.00', '-12287700',   2],
            ['-123.0',  '999',    '-1228770',    1],
            ['-123.0',  '999.0',  '-12287700',   2],
            ['-123.0',  '999.00', '-122877000',  3],
            ['-123.00', '999',    '-12287700',   2],
            ['-123.00', '999.0',  '-122877000',  3],
            ['-123.00', '999.00', '-1228770000', 4],

            ['-123',    '-999',    '122877',     0],
            ['-123',    '-999.0',  '1228770',    1],
            ['-123',    '-999.00', '12287700',   2],
            ['-123.0',  '-999',    '1228770',    1],
            ['-123.0',  '-999.0',  '12287700',   2],
            ['-123.0',  '-999.00', '122877000',  3],
            ['-123.00', '-999',    '12287700',   2],
            ['-123.00', '-999.0',  '122877000',  3],
            ['-123.00', '-999.00', '1228770000', 4],

            ['589252.156111130', '999.2563989942545241223454', '5888139876152080735720775399923986443020', 31],
            ['-589252.15611130', '999.256398994254524122354', '-58881398761537794715991163083004200020', 29],
            ['589252.1561113', '-99.256398994254524122354', '-584870471152079471599116308300420002', 28],
            ['-58952.156111', '-9.256398994254524122357', '545684678534996098129205129273627', 27]
        ];
    }

    /**
     * @dataProvider providerDividedBy
     *
     * @param string       $a             The base number.
     * @param string       $b             The number to multiply.
     * @param integer|null $scale         The desired scale of the result, or null to skip the parameter.
     * @param integer      $roundingMode  The rounding mode.
     * @param string       $unscaledValue The expected unscaled value of the result.
     * @param integer      $expectedScale The expected scale of the result.
     */
    public function testDividedBy($a, $b, $scale, $roundingMode, $unscaledValue, $expectedScale)
    {
        $decimal = BigDecimal::of($a)->dividedBy($b, $scale, $roundingMode);
        $this->assertBigDecimalEquals($unscaledValue, $expectedScale, $decimal);
    }

    /**
     * @return array
     */
    public function providerDividedBy()
    {
        return [
            [ '7',  '0.2', null, RoundingMode::UNNECESSARY,  '35', 0],
            [ '7', '-0.2', null, RoundingMode::UNNECESSARY, '-35', 0],
            ['-7',  '0.2', null, RoundingMode::UNNECESSARY, '-35', 0],
            ['-7', '-0.2', null, RoundingMode::UNNECESSARY,  '35', 0],

            ['1.5', '2', 2, RoundingMode::UNNECESSARY, '75', 2],
            ['0.123456789', '0.00244140625', 10, RoundingMode::UNNECESSARY, '505679007744', 10],
            ['1.234', '123.456', 50, RoundingMode::DOWN, '999546397096941420425090720580611715914981855883', 50],
            ['1', '3', 10, RoundingMode::UP, '3333333334', 10],
            ['0.124', '0.2', null, RoundingMode::UNNECESSARY, '620', 3],
            ['0.124', '2', null, RoundingMode::UNNECESSARY, '62', 3],
        ];
    }

    /**
     * @dataProvider providerDividedByZeroThrowsException
     * @expectedException \Brick\Math\ArithmeticException
     *
     * @param string|number $zero
     */
    public function testDividedByZeroThrowsException($zero)
    {
        BigDecimal::of(1)->dividedBy($zero);
    }

    /**
     * @return array
     */
    public function providerDividedByZeroThrowsException()
    {
        return [
            [0],
            ['0.0'],
            ['0.00']
        ];
    }

    /**
     * @dataProvider providerDividedByWithRoundingNecessaryThrowsException
     * @expectedException \Brick\Math\ArithmeticException
     *
     * @param string       $a     The base number.
     * @param string       $b     The number to divide by.
     * @param integer|null $scale The desired scale, or null to skip the parameter.
     */
    public function testDividedByWithRoundingNecessaryThrowsException($a, $b, $scale)
    {
        BigDecimal::of($a)->dividedBy($b, $scale);
    }

    /**
     * @return array
     */
    public function providerDividedByWithRoundingNecessaryThrowsException()
    {
        return [
            ['1.234', '123.456', null],
            ['7', '2', null],
            ['7', '3', 100]
        ];
    }

    /**
     * @dataProvider providerRoundingMode
     *
     * @param integer     $roundingMode The rounding mode.
     * @param string      $number       The number to round.
     * @param string|null $two          The expected rounding to a scale of two, or null if an exception is expected.
     * @param string|null $one          The expected rounding to a scale of one, or null if an exception is expected.
     * @param string|null $zero         The expected rounding to a scale of zero, or null if an exception is expected.
     */
    public function testRoundingMode($roundingMode, $number, $two, $one, $zero)
    {
        $number = BigDecimal::of($number);

        $this->doTestRoundingMode($roundingMode, $number, '1', $two, $one, $zero);
        $this->doTestRoundingMode($roundingMode, $number->negated(), '-1', $two, $one, $zero);
    }

    /**
     * @param integer     $roundingMode The rounding mode.
     * @param BigDecimal  $number       The number to round.
     * @param string      $divisor      The divisor.
     * @param string|null $two          The expected rounding to a scale of two, or null if an exception is expected.
     * @param string|null $one          The expected rounding to a scale of one, or null if an exception is expected.
     * @param string|null $zero         The expected rounding to a scale of zero, or null if an exception is expected.
     */
    private function doTestRoundingMode($roundingMode, BigDecimal $number, $divisor, $two, $one, $zero)
    {
        foreach ([$zero, $one, $two] as $scale => $expected) {
            if ($expected === null) {
                $this->setExpectedException(ArithmeticException::class);
            }

            $actual = $number->dividedBy($divisor, $scale, $roundingMode);

            if ($expected !== null) {
                $this->assertBigDecimalEquals($expected, $scale, $actual);
            }
        }
    }

    /**
     * @return array
     */
    public function providerRoundingMode()
    {
        return [
            [RoundingMode::UP,  '3.501',  '351',  '36',  '4'],
            [RoundingMode::UP,  '3.500',  '350',  '35',  '4'],
            [RoundingMode::UP,  '3.499',  '350',  '35',  '4'],
            [RoundingMode::UP,  '3.001',  '301',  '31',  '4'],
            [RoundingMode::UP,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::UP,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::UP,  '2.501',  '251',  '26',  '3'],
            [RoundingMode::UP,  '2.500',  '250',  '25',  '3'],
            [RoundingMode::UP,  '2.499',  '250',  '25',  '3'],
            [RoundingMode::UP,  '2.001',  '201',  '21',  '3'],
            [RoundingMode::UP,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::UP,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::UP,  '1.501',  '151',  '16',  '2'],
            [RoundingMode::UP,  '1.500',  '150',  '15',  '2'],
            [RoundingMode::UP,  '1.499',  '150',  '15',  '2'],
            [RoundingMode::UP,  '1.001',  '101',  '11',  '2'],
            [RoundingMode::UP,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::UP,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::UP,  '0.501',   '51',   '6',  '1'],
            [RoundingMode::UP,  '0.500',   '50',   '5',  '1'],
            [RoundingMode::UP,  '0.499',   '50',   '5',  '1'],
            [RoundingMode::UP,  '0.001',    '1',   '1',  '1'],
            [RoundingMode::UP,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::UP, '-0.001',   '-1',  '-1', '-1'],
            [RoundingMode::UP, '-0.499',  '-50',  '-5', '-1'],
            [RoundingMode::UP, '-0.500',  '-50',  '-5', '-1'],
            [RoundingMode::UP, '-0.501',  '-51',  '-6', '-1'],
            [RoundingMode::UP, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::UP, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::UP, '-1.001', '-101', '-11', '-2'],
            [RoundingMode::UP, '-1.499', '-150', '-15', '-2'],
            [RoundingMode::UP, '-1.500', '-150', '-15', '-2'],
            [RoundingMode::UP, '-1.501', '-151', '-16', '-2'],
            [RoundingMode::UP, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::UP, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::UP, '-2.001', '-201', '-21', '-3'],
            [RoundingMode::UP, '-2.499', '-250', '-25', '-3'],
            [RoundingMode::UP, '-2.500', '-250', '-25', '-3'],
            [RoundingMode::UP, '-2.501', '-251', '-26', '-3'],
            [RoundingMode::UP, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::UP, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::UP, '-3.001', '-301', '-31', '-4'],
            [RoundingMode::UP, '-3.499', '-350', '-35', '-4'],
            [RoundingMode::UP, '-3.500', '-350', '-35', '-4'],
            [RoundingMode::UP, '-3.501', '-351', '-36', '-4'],

            [RoundingMode::DOWN,  '3.501',  '350',  '35',  '3'],
            [RoundingMode::DOWN,  '3.500',  '350',  '35',  '3'],
            [RoundingMode::DOWN,  '3.499',  '349',  '34',  '3'],
            [RoundingMode::DOWN,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::DOWN,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::DOWN,  '2.999',  '299',  '29',  '2'],
            [RoundingMode::DOWN,  '2.501',  '250',  '25',  '2'],
            [RoundingMode::DOWN,  '2.500',  '250',  '25',  '2'],
            [RoundingMode::DOWN,  '2.499',  '249',  '24',  '2'],
            [RoundingMode::DOWN,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::DOWN,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::DOWN,  '1.999',  '199',  '19',  '1'],
            [RoundingMode::DOWN,  '1.501',  '150',  '15',  '1'],
            [RoundingMode::DOWN,  '1.500',  '150',  '15',  '1'],
            [RoundingMode::DOWN,  '1.499',  '149',  '14',  '1'],
            [RoundingMode::DOWN,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::DOWN,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::DOWN,  '0.999',   '99',   '9',  '0'],
            [RoundingMode::DOWN,  '0.501',   '50',   '5',  '0'],
            [RoundingMode::DOWN,  '0.500',   '50',   '5',  '0'],
            [RoundingMode::DOWN,  '0.499',   '49',   '4',  '0'],
            [RoundingMode::DOWN,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::DOWN,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::DOWN, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::DOWN, '-0.499',  '-49',  '-4',  '0'],
            [RoundingMode::DOWN, '-0.500',  '-50',  '-5',  '0'],
            [RoundingMode::DOWN, '-0.501',  '-50',  '-5',  '0'],
            [RoundingMode::DOWN, '-0.999',  '-99',  '-9',  '0'],
            [RoundingMode::DOWN, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::DOWN, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::DOWN, '-1.499', '-149', '-14', '-1'],
            [RoundingMode::DOWN, '-1.500', '-150', '-15', '-1'],
            [RoundingMode::DOWN, '-1.501', '-150', '-15', '-1'],
            [RoundingMode::DOWN, '-1.999', '-199', '-19', '-1'],
            [RoundingMode::DOWN, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::DOWN, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::DOWN, '-2.499', '-249', '-24', '-2'],
            [RoundingMode::DOWN, '-2.500', '-250', '-25', '-2'],
            [RoundingMode::DOWN, '-2.501', '-250', '-25', '-2'],
            [RoundingMode::DOWN, '-2.999', '-299', '-29', '-2'],
            [RoundingMode::DOWN, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::DOWN, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::DOWN, '-3.499', '-349', '-34', '-3'],
            [RoundingMode::DOWN, '-3.500', '-350', '-35', '-3'],
            [RoundingMode::DOWN, '-3.501', '-350', '-35', '-3'],

            [RoundingMode::CEILING,  '3.501',  '351',  '36',  '4'],
            [RoundingMode::CEILING,  '3.500',  '350',  '35',  '4'],
            [RoundingMode::CEILING,  '3.499',  '350',  '35',  '4'],
            [RoundingMode::CEILING,  '3.001',  '301',  '31',  '4'],
            [RoundingMode::CEILING,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::CEILING,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::CEILING,  '2.501',  '251',  '26',  '3'],
            [RoundingMode::CEILING,  '2.500',  '250',  '25',  '3'],
            [RoundingMode::CEILING,  '2.499',  '250',  '25',  '3'],
            [RoundingMode::CEILING,  '2.001',  '201',  '21',  '3'],
            [RoundingMode::CEILING,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::CEILING,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::CEILING,  '1.501',  '151',  '16',  '2'],
            [RoundingMode::CEILING,  '1.500',  '150',  '15',  '2'],
            [RoundingMode::CEILING,  '1.499',  '150',  '15',  '2'],
            [RoundingMode::CEILING,  '1.001',  '101',  '11',  '2'],
            [RoundingMode::CEILING,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::CEILING,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::CEILING,  '0.501',   '51',   '6',  '1'],
            [RoundingMode::CEILING,  '0.500',   '50',   '5',  '1'],
            [RoundingMode::CEILING,  '0.499',   '50',   '5',  '1'],
            [RoundingMode::CEILING,  '0.001',    '1',   '1',  '1'],
            [RoundingMode::CEILING,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::CEILING, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::CEILING, '-0.499',  '-49' , '-4',  '0'],
            [RoundingMode::CEILING, '-0.500',  '-50' , '-5',  '0'],
            [RoundingMode::CEILING, '-0.501',  '-50',  '-5',  '0'],
            [RoundingMode::CEILING, '-0.999',  '-99',  '-9',  '0'],
            [RoundingMode::CEILING, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::CEILING, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::CEILING, '-1.499', '-149', '-14', '-1'],
            [RoundingMode::CEILING, '-1.500', '-150', '-15', '-1'],
            [RoundingMode::CEILING, '-1.501', '-150', '-15', '-1'],
            [RoundingMode::CEILING, '-1.999', '-199', '-19', '-1'],
            [RoundingMode::CEILING, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::CEILING, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::CEILING, '-2.499', '-249', '-24', '-2'],
            [RoundingMode::CEILING, '-2.500', '-250', '-25', '-2'],
            [RoundingMode::CEILING, '-2.501', '-250', '-25', '-2'],
            [RoundingMode::CEILING, '-2.999', '-299', '-29', '-2'],
            [RoundingMode::CEILING, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::CEILING, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::CEILING, '-3.499', '-349', '-34', '-3'],
            [RoundingMode::CEILING, '-3.500', '-350', '-35', '-3'],
            [RoundingMode::CEILING, '-3.501', '-350', '-35', '-3'],

            [RoundingMode::FLOOR,  '3.501',  '350',  '35',  '3'],
            [RoundingMode::FLOOR,  '3.500',  '350',  '35',  '3'],
            [RoundingMode::FLOOR,  '3.499',  '349',  '34',  '3'],
            [RoundingMode::FLOOR,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::FLOOR,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::FLOOR,  '2.999',  '299',  '29',  '2'],
            [RoundingMode::FLOOR,  '2.501',  '250',  '25',  '2'],
            [RoundingMode::FLOOR,  '2.500',  '250',  '25',  '2'],
            [RoundingMode::FLOOR,  '2.499',  '249',  '24',  '2'],
            [RoundingMode::FLOOR,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::FLOOR,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::FLOOR,  '1.999',  '199',  '19',  '1'],
            [RoundingMode::FLOOR,  '1.501',  '150',  '15',  '1'],
            [RoundingMode::FLOOR,  '1.500',  '150',  '15',  '1'],
            [RoundingMode::FLOOR,  '1.499',  '149',  '14',  '1'],
            [RoundingMode::FLOOR,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::FLOOR,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::FLOOR,  '0.999',   '99',   '9',  '0'],
            [RoundingMode::FLOOR,  '0.501',   '50',   '5',  '0'],
            [RoundingMode::FLOOR,  '0.500',   '50',   '5',  '0'],
            [RoundingMode::FLOOR,  '0.499',   '49',   '4',  '0'],
            [RoundingMode::FLOOR,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::FLOOR,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::FLOOR, '-0.001',   '-1',  '-1', '-1'],
            [RoundingMode::FLOOR, '-0.499',  '-50',  '-5', '-1'],
            [RoundingMode::FLOOR, '-0.500',  '-50',  '-5', '-1'],
            [RoundingMode::FLOOR, '-0.501',  '-51',  '-6', '-1'],
            [RoundingMode::FLOOR, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::FLOOR, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::FLOOR, '-1.001', '-101', '-11', '-2'],
            [RoundingMode::FLOOR, '-1.499', '-150', '-15', '-2'],
            [RoundingMode::FLOOR, '-1.500', '-150', '-15', '-2'],
            [RoundingMode::FLOOR, '-1.501', '-151', '-16', '-2'],
            [RoundingMode::FLOOR, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::FLOOR, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::FLOOR, '-2.001', '-201', '-21', '-3'],
            [RoundingMode::FLOOR, '-2.499', '-250', '-25', '-3'],
            [RoundingMode::FLOOR, '-2.500', '-250', '-25', '-3'],
            [RoundingMode::FLOOR, '-2.501', '-251', '-26', '-3'],
            [RoundingMode::FLOOR, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::FLOOR, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::FLOOR, '-3.001', '-301', '-31', '-4'],
            [RoundingMode::FLOOR, '-3.499', '-350', '-35', '-4'],
            [RoundingMode::FLOOR, '-3.500', '-350', '-35', '-4'],
            [RoundingMode::FLOOR, '-3.501', '-351', '-36', '-4'],

            [RoundingMode::HALF_UP,  '3.501',  '350',  '35',  '4'],
            [RoundingMode::HALF_UP,  '3.500',  '350',  '35',  '4'],
            [RoundingMode::HALF_UP,  '3.499',  '350',  '35',  '3'],
            [RoundingMode::HALF_UP,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::HALF_UP,  '2.501',  '250',  '25',  '3'],
            [RoundingMode::HALF_UP,  '2.500',  '250',  '25',  '3'],
            [RoundingMode::HALF_UP,  '2.499',  '250',  '25',  '2'],
            [RoundingMode::HALF_UP,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::HALF_UP,  '1.501',  '150',  '15',  '2'],
            [RoundingMode::HALF_UP,  '1.500',  '150',  '15',  '2'],
            [RoundingMode::HALF_UP,  '1.499',  '150',  '15',  '1'],
            [RoundingMode::HALF_UP,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::HALF_UP,  '0.501',   '50',   '5',  '1'],
            [RoundingMode::HALF_UP,  '0.500',   '50',   '5',  '1'],
            [RoundingMode::HALF_UP,  '0.499',   '50',   '5',  '0'],
            [RoundingMode::HALF_UP,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_UP, '-0.499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_UP, '-0.500',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_UP, '-0.501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_UP, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::HALF_UP, '-1.499', '-150', '-15', '-1'],
            [RoundingMode::HALF_UP, '-1.500', '-150', '-15', '-2'],
            [RoundingMode::HALF_UP, '-1.501', '-150', '-15', '-2'],
            [RoundingMode::HALF_UP, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::HALF_UP, '-2.499', '-250', '-25', '-2'],
            [RoundingMode::HALF_UP, '-2.500', '-250', '-25', '-3'],
            [RoundingMode::HALF_UP, '-2.501', '-250', '-25', '-3'],
            [RoundingMode::HALF_UP, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::HALF_UP, '-3.499', '-350', '-35', '-3'],
            [RoundingMode::HALF_UP, '-3.500', '-350', '-35', '-4'],
            [RoundingMode::HALF_UP, '-3.501', '-350', '-35', '-4'],

            [RoundingMode::HALF_DOWN,  '3.501',  '350',  '35',  '4'],
            [RoundingMode::HALF_DOWN,  '3.500',  '350',  '35',  '3'],
            [RoundingMode::HALF_DOWN,  '3.499',  '350',  '35',  '3'],
            [RoundingMode::HALF_DOWN,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::HALF_DOWN,  '2.501',  '250',  '25',  '3'],
            [RoundingMode::HALF_DOWN,  '2.500',  '250',  '25',  '2'],
            [RoundingMode::HALF_DOWN,  '2.499',  '250',  '25',  '2'],
            [RoundingMode::HALF_DOWN,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::HALF_DOWN,  '1.501',  '150',  '15',  '2'],
            [RoundingMode::HALF_DOWN,  '1.500',  '150',  '15',  '1'],
            [RoundingMode::HALF_DOWN,  '1.499',  '150',  '15',  '1'],
            [RoundingMode::HALF_DOWN,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::HALF_DOWN,  '0.501',   '50',   '5',  '1'],
            [RoundingMode::HALF_DOWN,  '0.500',   '50',   '5',  '0'],
            [RoundingMode::HALF_DOWN,  '0.499',   '50',   '5',  '0'],
            [RoundingMode::HALF_DOWN,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_DOWN, '-0.499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_DOWN, '-0.500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_DOWN, '-0.501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_DOWN, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::HALF_DOWN, '-1.499', '-150', '-15', '-1'],
            [RoundingMode::HALF_DOWN, '-1.500', '-150', '-15', '-1'],
            [RoundingMode::HALF_DOWN, '-1.501', '-150', '-15', '-2'],
            [RoundingMode::HALF_DOWN, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::HALF_DOWN, '-2.499', '-250', '-25', '-2'],
            [RoundingMode::HALF_DOWN, '-2.500', '-250', '-25', '-2'],
            [RoundingMode::HALF_DOWN, '-2.501', '-250', '-25', '-3'],
            [RoundingMode::HALF_DOWN, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::HALF_DOWN, '-3.499', '-350', '-35', '-3'],
            [RoundingMode::HALF_DOWN, '-3.500', '-350', '-35', '-3'],
            [RoundingMode::HALF_DOWN, '-3.501', '-350', '-35', '-4'],

            [RoundingMode::HALF_CEILING,  '3.501',  '350',  '35',  '4'],
            [RoundingMode::HALF_CEILING,  '3.500',  '350',  '35',  '4'],
            [RoundingMode::HALF_CEILING,  '3.499',  '350',  '35',  '3'],
            [RoundingMode::HALF_CEILING,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::HALF_CEILING,  '2.501',  '250',  '25',  '3'],
            [RoundingMode::HALF_CEILING,  '2.500',  '250',  '25',  '3'],
            [RoundingMode::HALF_CEILING,  '2.499',  '250',  '25',  '2'],
            [RoundingMode::HALF_CEILING,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::HALF_CEILING,  '1.501',  '150',  '15',  '2'],
            [RoundingMode::HALF_CEILING,  '1.500',  '150',  '15',  '2'],
            [RoundingMode::HALF_CEILING,  '1.499',  '150',  '15',  '1'],
            [RoundingMode::HALF_CEILING,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::HALF_CEILING,  '0.501',   '50',   '5',  '1'],
            [RoundingMode::HALF_CEILING,  '0.500',   '50',   '5',  '1'],
            [RoundingMode::HALF_CEILING,  '0.499',   '50',   '5',  '0'],
            [RoundingMode::HALF_CEILING,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_CEILING, '-0.499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_CEILING, '-0.500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_CEILING, '-0.501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_CEILING, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::HALF_CEILING, '-1.499', '-150', '-15', '-1'],
            [RoundingMode::HALF_CEILING, '-1.500', '-150', '-15', '-1'],
            [RoundingMode::HALF_CEILING, '-1.501', '-150', '-15', '-2'],
            [RoundingMode::HALF_CEILING, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::HALF_CEILING, '-2.499', '-250', '-25', '-2'],
            [RoundingMode::HALF_CEILING, '-2.500', '-250', '-25', '-2'],
            [RoundingMode::HALF_CEILING, '-2.501', '-250', '-25', '-3'],
            [RoundingMode::HALF_CEILING, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::HALF_CEILING, '-3.499', '-350', '-35', '-3'],
            [RoundingMode::HALF_CEILING, '-3.500', '-350', '-35', '-3'],
            [RoundingMode::HALF_CEILING, '-3.501', '-350', '-35', '-4'],

            [RoundingMode::HALF_FLOOR,  '3.501',  '350',  '35',  '4'],
            [RoundingMode::HALF_FLOOR,  '3.500',  '350',  '35',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.499',  '350',  '35',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.501',  '250',  '25',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.500',  '250',  '25',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.499',  '250',  '25',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.501',  '150',  '15',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.500',  '150',  '15',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.499',  '150',  '15',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.501',   '50',   '5',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.500',   '50',   '5',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.499',   '50',   '5',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.500',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_FLOOR, '-0.501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_FLOOR, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.499', '-150', '-15', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.500', '-150', '-15', '-2'],
            [RoundingMode::HALF_FLOOR, '-1.501', '-150', '-15', '-2'],
            [RoundingMode::HALF_FLOOR, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.499', '-250', '-25', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.500', '-250', '-25', '-3'],
            [RoundingMode::HALF_FLOOR, '-2.501', '-250', '-25', '-3'],
            [RoundingMode::HALF_FLOOR, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.499', '-350', '-35', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.500', '-350', '-35', '-4'],
            [RoundingMode::HALF_FLOOR, '-3.501', '-350', '-35', '-4'],

            [RoundingMode::HALF_EVEN,  '3.501',  '350',  '35',  '4'],
            [RoundingMode::HALF_EVEN,  '3.500',  '350',  '35',  '4'],
            [RoundingMode::HALF_EVEN,  '3.499',  '350',  '35',  '3'],
            [RoundingMode::HALF_EVEN,  '3.001',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '2.999',  '300',  '30',  '3'],
            [RoundingMode::HALF_EVEN,  '2.501',  '250',  '25',  '3'],
            [RoundingMode::HALF_EVEN,  '2.500',  '250',  '25',  '2'],
            [RoundingMode::HALF_EVEN,  '2.499',  '250',  '25',  '2'],
            [RoundingMode::HALF_EVEN,  '2.001',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '1.999',  '200',  '20',  '2'],
            [RoundingMode::HALF_EVEN,  '1.501',  '150',  '15',  '2'],
            [RoundingMode::HALF_EVEN,  '1.500',  '150',  '15',  '2'],
            [RoundingMode::HALF_EVEN,  '1.499',  '150',  '15',  '1'],
            [RoundingMode::HALF_EVEN,  '1.001',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,  '0.999',  '100',  '10',  '1'],
            [RoundingMode::HALF_EVEN,  '0.501',   '50',   '5',  '1'],
            [RoundingMode::HALF_EVEN,  '0.500',   '50',   '5',  '0'],
            [RoundingMode::HALF_EVEN,  '0.499',   '50',   '5',  '0'],
            [RoundingMode::HALF_EVEN,  '0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN, '-0.001',    '0',   '0',  '0'],
            [RoundingMode::HALF_EVEN, '-0.499',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_EVEN, '-0.500',  '-50',  '-5',  '0'],
            [RoundingMode::HALF_EVEN, '-0.501',  '-50',  '-5', '-1'],
            [RoundingMode::HALF_EVEN, '-0.999', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1.001', '-100', '-10', '-1'],
            [RoundingMode::HALF_EVEN, '-1.499', '-150', '-15', '-1'],
            [RoundingMode::HALF_EVEN, '-1.500', '-150', '-15', '-2'],
            [RoundingMode::HALF_EVEN, '-1.501', '-150', '-15', '-2'],
            [RoundingMode::HALF_EVEN, '-1.999', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2.001', '-200', '-20', '-2'],
            [RoundingMode::HALF_EVEN, '-2.499', '-250', '-25', '-2'],
            [RoundingMode::HALF_EVEN, '-2.500', '-250', '-25', '-2'],
            [RoundingMode::HALF_EVEN, '-2.501', '-250', '-25', '-3'],
            [RoundingMode::HALF_EVEN, '-2.999', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3.001', '-300', '-30', '-3'],
            [RoundingMode::HALF_EVEN, '-3.499', '-350', '-35', '-3'],
            [RoundingMode::HALF_EVEN, '-3.500', '-350', '-35', '-4'],
            [RoundingMode::HALF_EVEN, '-3.501', '-350', '-35', '-4'],

            [RoundingMode::UNNECESSARY,  '3.501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3.500',  '350',  '35', null],
            [RoundingMode::UNNECESSARY,  '3.499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3.001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '3.000',  '300',  '30',  '3'],
            [RoundingMode::UNNECESSARY,  '2.999',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2.501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2.500',  '250',  '25', null],
            [RoundingMode::UNNECESSARY,  '2.499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2.001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '2.000',  '200',  '20',  '2'],
            [RoundingMode::UNNECESSARY,  '1.999',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1.501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1.500',  '150',  '15', null],
            [RoundingMode::UNNECESSARY,  '1.499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1.001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '1.000',  '100',  '10',  '1'],
            [RoundingMode::UNNECESSARY,  '0.999',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '0.501',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '0.500',   '50',   '5', null],
            [RoundingMode::UNNECESSARY,  '0.499',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '0.001',   null,  null, null],
            [RoundingMode::UNNECESSARY,  '0.000',    '0',   '0',  '0'],
            [RoundingMode::UNNECESSARY, '-0.001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-0.499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-0.500',  '-50',  '-5', null],
            [RoundingMode::UNNECESSARY, '-0.501',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-0.999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1.000', '-100', '-10', '-1'],
            [RoundingMode::UNNECESSARY, '-1.001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1.499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1.500', '-150', '-15', null],
            [RoundingMode::UNNECESSARY, '-1.501',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-1.999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2.000', '-200', '-20', '-2'],
            [RoundingMode::UNNECESSARY, '-2.001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2.499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2.500', '-250', '-25', null],
            [RoundingMode::UNNECESSARY, '-2.501',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-2.999',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3.000', '-300', '-30', '-3'],
            [RoundingMode::UNNECESSARY, '-3.001',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3.499',   null,  null, null],
            [RoundingMode::UNNECESSARY, '-3.500', '-350', '-35', null],
            [RoundingMode::UNNECESSARY, '-3.501',   null,  null, null],
        ];
    }

    /**
     * @dataProvider providerPower
     *
     * @param string  $number        The base number.
     * @param integer $exponent      The exponent to apply.
     * @param string  $unscaledValue The expected unscaled value of the result.
     * @param integer $scale         The expected scale of the result.
     */
    public function testPower($number, $exponent, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($number)->power($exponent));
    }

    /**
     * @return array
     */
    public function providerPower()
    {
        return [
            ['-3', 0, '1', 0],
            ['-2', 0, '1', 0],
            ['-1', 0, '1', 0],
            ['0',  0, '1', 0],
            ['1',  0, '1', 0],
            ['2',  0, '1', 0],
            ['3',  0, '1', 0],

            ['-3', 1, '-3', 0],
            ['-2', 1, '-2', 0],
            ['-1', 1, '-1', 0],
            ['0',  1,  '0', 0],
            ['1',  1,  '1', 0],
            ['2',  1,  '2', 0],
            ['3',  1,  '3', 0],

            ['-3', 2, '9', 0],
            ['-2', 2, '4', 0],
            ['-1', 2, '1', 0],
            ['0',  2, '0', 0],
            ['1',  2, '1', 0],
            ['2',  2, '4', 0],
            ['3',  2, '9', 0],

            ['-3', 3, '-27', 0],
            ['-2', 3,  '-8', 0],
            ['-1', 3,  '-1', 0],
            ['0',  3,   '0', 0],
            ['1',  3,   '1', 0],
            ['2',  3,   '8', 0],
            ['3',  3,  '27', 0],

            ['0', PHP_INT_MAX, '0', 0],
            ['1', PHP_INT_MAX, '1', 0],

            ['-2', 255, '-57896044618658097711785492504343953926634992332820282019728792003956564819968', 0],
            [ '2', 256, '115792089237316195423570985008687907853269984665640564039457584007913129639936', 0],

            ['-1.23', 33, '-926549609804623448265268294182900512918058893428212027689876489708283', 66],
            [ '1.23', 34, '113965602005968684136628000184496763088921243891670079405854808234118809', 68],

            ['-123456789', 8, '53965948844821664748141453212125737955899777414752273389058576481', 0],
            ['9876543210', 7, '9167159269868350921847491739460569765344716959834325922131706410000000', 0]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPowerWithNegativeExponentThrowsException()
    {
        BigDecimal::of(10)->power(-1);
    }

    /**
     * @dataProvider withScaleProvider
     *
     * @param string  $number        The number to scale.
     * @param integer $withScale     The scale to apply.
     * @param integer $roundingMode  The rounding mode to apply.
     * @param string  $unscaledValue The expected unscaled value of the result.
     * @param integer $scale         The expected scale of the result.
     */
    public function testWithScale($number, $withScale, $roundingMode, $unscaledValue, $scale)
    {
        $decimal = BigDecimal::of($number)->withScale($withScale, $roundingMode);
        $this->assertBigDecimalEquals($unscaledValue, $scale, $decimal);
    }

    /**
     * @return array
     */
    public function withScaleProvider()
    {
        return [
            ['123.45', 0, RoundingMode::DOWN, '123', 0],
            ['123.45', 1, RoundingMode::UP, '1235', 1],
            ['123.45', 2, RoundingMode::UNNECESSARY, '12345', 2],
            ['123.45', 5, RoundingMode::UNNECESSARY, '12345000', 5]
        ];
    }

    /**
     * @dataProvider providerWithPointMovedLeft
     *
     * @param string  $number   The decimal number as a string.
     * @param integer $places   The number of decimal places to move left.
     * @param string  $expected The expected result.
     */
    public function testWithPointMovedLeft($number, $places, $expected)
    {
        $this->assertSame($expected, (string) BigDecimal::of($number)->withPointMovedLeft($places));
    }

    /**
     * @return array
     */
    public function providerWithPointMovedLeft()
    {
        return [
            ['0', -2, '0'],
            ['0', -1, '0'],
            ['0', 0, '0'],
            ['0', 1, '0.0'],
            ['0', 2, '0.00'],

            ['0.0', -2, '0'],
            ['0.0', -1, '0'],
            ['0.0', 0, '0.0'],
            ['0.0', 1, '0.00'],
            ['0.0', 2, '0.000'],

            ['1', -2, '100'],
            ['1', -1, '10'],
            ['1', 0, '1'],
            ['1', 1, '0.1'],
            ['1', 2, '0.01'],

            ['12', -2, '1200'],
            ['12', -1, '120'],
            ['12', 0, '12'],
            ['12', 1, '1.2'],
            ['12', 2, '0.12'],

            ['1.1', -2, '110'],
            ['1.1', -1, '11'],
            ['1.1', 0, '1.1'],
            ['1.1', 1, '0.11'],
            ['1.1', 2, '0.011'],

            ['0.1', -2, '10'],
            ['0.1', -1, '1'],
            ['0.1', 0, '0.1'],
            ['0.1', 1, '0.01'],
            ['0.1', 2, '0.001'],

            ['0.01', -2, '1'],
            ['0.01', -1, '0.1'],
            ['0.01', 0, '0.01'],
            ['0.01', 1, '0.001'],
            ['0.01', 2, '0.0001'],

            ['-9', -2, '-900'],
            ['-9', -1, '-90'],
            ['-9', 0, '-9'],
            ['-9', 1, '-0.9'],
            ['-9', 2, '-0.09'],

            ['-0.9', -2, '-90'],
            ['-0.9', -1, '-9'],
            ['-0.9', 0, '-0.9'],
            ['-0.9', 1, '-0.09'],
            ['-0.9', 2, '-0.009'],

            ['-0.09', -2, '-9'],
            ['-0.09', -1, '-0.9'],
            ['-0.09', 0, '-0.09'],
            ['-0.09', 1, '-0.009'],
            ['-0.09', 2, '-0.0009'],

            ['-12.3', -2, '-1230'],
            ['-12.3', -1, '-123'],
            ['-12.3', 0, '-12.3'],
            ['-12.3', 1, '-1.23'],
            ['-12.3', 2, '-0.123'],
        ];
    }

    /**
     * @dataProvider providerWithPointMovedRight
     *
     * @param string  $number   The decimal number as a string.
     * @param integer $places   The number of decimal places to move right.
     * @param string  $expected The expected result.
     */
    public function testWithPointMovedRight($number, $places, $expected)
    {
        $this->assertSame($expected, (string) BigDecimal::of($number)->withPointMovedRight($places));
    }

    /**
     * @return array
     */
    public function providerWithPointMovedRight()
    {
        return [
            ['0', -2, '0.00'],
            ['0', -1, '0.0'],
            ['0', 0, '0'],
            ['0', 1, '0'],
            ['0', 2, '0'],

            ['0.0', -2, '0.000'],
            ['0.0', -1, '0.00'],
            ['0.0', 0, '0.0'],
            ['0.0', 1, '0'],
            ['0.0', 2, '0'],

            ['9', -2, '0.09'],
            ['9', -1, '0.9'],
            ['9', 0, '9'],
            ['9', 1, '90'],
            ['9', 2, '900'],

            ['89', -2, '0.89'],
            ['89', -1, '8.9'],
            ['89', 0, '89'],
            ['89', 1, '890'],
            ['89', 2, '8900'],

            ['8.9', -2, '0.089'],
            ['8.9', -1, '0.89'],
            ['8.9', 0, '8.9'],
            ['8.9', 1, '89'],
            ['8.9', 2, '890'],

            ['0.9', -2, '0.009'],
            ['0.9', -1, '0.09'],
            ['0.9', 0, '0.9'],
            ['0.9', 1, '9'],
            ['0.9', 2, '90'],

            ['0.09', -2, '0.0009'],
            ['0.09', -1, '0.009'],
            ['0.09', 0, '0.09'],
            ['0.09', 1, '0.9'],
            ['0.09', 2, '9'],

            ['-1', -2, '-0.01'],
            ['-1', -1, '-0.1'],
            ['-1', 0, '-1'],
            ['-1', 1, '-10'],
            ['-1', 2, '-100'],

            ['-0.1', -2, '-0.001'],
            ['-0.1', -1, '-0.01'],
            ['-0.1', 0, '-0.1'],
            ['-0.1', 1, '-1'],
            ['-0.1', 2, '-10'],

            ['-0.01', -2, '-0.0001'],
            ['-0.01', -1, '-0.001'],
            ['-0.01', 0, '-0.01'],
            ['-0.01', 1, '-0.1'],
            ['-0.01', 2, '-1'],

            ['-12.3', -2, '-0.123'],
            ['-12.3', -1, '-1.23'],
            ['-12.3', 0, '-12.3'],
            ['-12.3', 1, '-123'],
            ['-12.3', 2, '-1230'],
        ];
    }

    /**
     * @dataProvider providerAbs
     *
     * @param string  $number        The number as a string.
     * @param string  $unscaledValue The expected unscaled value of the absolute result.
     * @param integer $scale         The expected scale of the absolute result.
     */
    public function testAbs($number, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($number)->abs());
    }

    /**
     * @return array
     */
    public function providerAbs()
    {
        return [
            ['123', '123', 0],
            ['-123', '123', 0],
            ['123.456', '123456', 3],
            ['-123.456', '123456', 3]
        ];
    }

    /**
     * @dataProvider providerNegated
     *
     * @param string  $number        The number to negate as a string.
     * @param string  $unscaledValue The expected unscaled value of the result.
     * @param integer $scale         The expected scale of the result.
     */
    public function testNegated($number, $unscaledValue, $scale)
    {
        $this->assertBigDecimalEquals($unscaledValue, $scale, BigDecimal::of($number)->negated());
    }

    /**
     * @return array
     */
    public function providerNegated()
    {
        return [
            ['123', '-123', 0],
            ['-123', '123', 0],
            ['123.456', '-123456', 3],
            ['-123.456', '123456', 3]
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
        $this->assertSame($c, BigDecimal::of($a)->compareTo($b));
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
        $this->assertSame($c == 0, BigDecimal::of($a)->isEqualTo($b));
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
        $this->assertSame($c < 0, BigDecimal::of($a)->isLessThan($b));
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
        $this->assertSame($c <= 0, BigDecimal::of($a)->isLessThanOrEqualTo($b));
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
        $this->assertSame($c > 0, BigDecimal::of($a)->isGreaterThan($b));
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
        $this->assertSame($c >= 0, BigDecimal::of($a)->isGreaterThanOrEqualTo($b));
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

            ['123.000000000000000000000000000000000000000000000', '123',  0],
            ['123.000000000000000000000000000000000000000000001', '123',  1],
            ['122.999999999999999999999999999999999999999999999', '123', -1],

            ['123.0', '123.000000000000000000000000000000000000000000000',  0],
            ['123.0', '123.000000000000000000000000000000000000000000001', -1],
            ['123.0', '122.999999999999999999999999999999999999999999999',  1],

            ['-0.000000000000000000000000000000000000000000000000001', '0', -1],
            [ '0.000000000000000000000000000000000000000000000000001', '0',  1],
            [ '0.000000000000000000000000000000000000000000000000000', '0',  0],

            ['0', '-0.000000000000000000000000000000000000000000000000001',  1],
            ['0',  '0.000000000000000000000000000000000000000000000000001', -1],
            ['0',  '0.000000000000000000000000000000000000000000000000000',  0]
        ];
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|number $number The number to test.
     * @param boolean       $cmp    The comparison value.
     */
    public function testIsZero($number, $cmp)
    {
        $this->assertSame($cmp == 0, BigDecimal::of($number)->isZero());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|number $number The number to test.
     * @param boolean       $cmp    The comparison value.
     */
    public function testIsNegative($number, $cmp)
    {
        $this->assertSame($cmp < 0, BigDecimal::of($number)->isNegative());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|number $number The number to test.
     * @param boolean       $cmp    The comparison value.
     */
    public function testIsNegativeOrZero($number, $cmp)
    {
        $this->assertSame($cmp <= 0, BigDecimal::of($number)->isNegativeOrZero());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|number $number The number to test.
     * @param boolean       $cmp    The comparison value.
     */
    public function testIsPositive($number, $cmp)
    {
        $this->assertSame($cmp > 0, BigDecimal::of($number)->isPositive());
    }

    /**
     * @dataProvider providerCompareToZero
     *
     * @param string|number $number The number to test.
     * @param boolean       $cmp    The comparison value.
     */
    public function testIsPositiveOrZero($number, $cmp)
    {
        $this->assertSame($cmp >= 0, BigDecimal::of($number)->isPositiveOrZero());
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

            [ 1.0,  1],
            [-1.0, -1],
            [ 0.1,  1],
            [-0.1, -1],
            [ 0.0,  0],
            [-0.0,  0],

            [ '1.00',  1],
            ['-1.00', -1],
            [ '0.10',  1],
            ['-0.10', -1],
            [ '0.01',  1],
            ['-0.01', -1],
            [ '0.00',  0],
            ['-0.00',  0],

            [ '0.000000000000000000000000000000000000000000000000000000000000000000000000000001',  1],
            [ '0.000000000000000000000000000000000000000000000000000000000000000000000000000000',  0],
            ['-0.000000000000000000000000000000000000000000000000000000000000000000000000000001', -1]
        ];
    }

    /**
     * @dataProvider providerGetIntegral
     *
     * @param string $number   The number to test.
     * @param string $expected The expected integral value.
     */
    public function testGetIntegral($number, $expected)
    {
        $this->assertSame($expected, BigDecimal::of($number)->getIntegral());
    }

    /**
     * @return array
     */
    public function providerGetIntegral()
    {
        return [
            ['1.23', '1'],
            ['-1.23', '-1'],
            ['0.123', '0'],
            ['0.001', '0'],
            ['123.0', '123'],
            ['12', '12'],
            ['1234.5678', '1234']
        ];
    }

    /**
     * @dataProvider providerGetFraction
     *
     * @param string $number   The number to test.
     * @param string $expected The expected fractional value.
     */
    public function testGetFraction($number, $expected)
    {
        $this->assertSame($expected, BigDecimal::of($number)->getFraction());
    }

    /**
     * @return array
     */
    public function providerGetFraction()
    {
        return [
            ['1.23', '23'],
            ['-1.23', '23'],
            ['1', ''],
            ['-1', ''],
            ['0', ''],
            ['0.001', '001']
        ];
    }

    /**
     * @dataProvider providerToBigInteger
     *
     * @param string  $decimal      The number to convert.
     * @param integer $roundingMode The rounding mode.
     * @param string  $expected     The expected value.
     */
    public function testToBigInteger($decimal, $roundingMode, $expected)
    {
        $this->assertBigIntegerEquals($expected, BigDecimal::of($decimal)->toBigInteger($roundingMode));
    }

    /**
     * @return array
     */
    public function providerToBigInteger()
    {
        return [
            ['0',   RoundingMode::UNNECESSARY, '0'],
            ['0',   RoundingMode::DOWN, '0'],
            ['0',   RoundingMode::UP, '0'],
            ['1',   RoundingMode::UNNECESSARY, '1'],
            ['1',   RoundingMode::DOWN, '1'],
            ['1',   RoundingMode::UP, '1'],
            ['0.0', RoundingMode::UNNECESSARY, '0'],
            ['0.0', RoundingMode::DOWN, '0'],
            ['0.0', RoundingMode::UP, '0'],
            ['0.1', RoundingMode::DOWN, '0'],
            ['0.1', RoundingMode::UP, '1'],
            ['1.0', RoundingMode::UNNECESSARY, '1'],
            ['1.0', RoundingMode::DOWN, '1'],
            ['1.0', RoundingMode::UP, '1'],
            ['1.1', RoundingMode::DOWN,        '1'],
            ['1.1', RoundingMode::UP, '2'],
            ['0.01', RoundingMode::DOWN, '0'],
            ['0.01', RoundingMode::UP, '1'],
            ['1.01', RoundingMode::DOWN, '1'],
            ['1.01', RoundingMode::UP, '2']
        ];
    }

    /**
     * @dataProvider providerToBigIntegerThrowsExceptionWhenRoundingNecessary
     * @expectedException \Brick\Math\ArithmeticException
     *
     * @param string $decimal
     */
    public function testToBigIntegerThrowsExceptionWhenRoundingNecessary($decimal)
    {
        BigDecimal::of($decimal)->toBigInteger();
    }

    /**
     * @return array
     */
    public function providerToBigIntegerThrowsExceptionWhenRoundingNecessary()
    {
        return [
            ['0.1'],
            ['-0.1'],
            ['0.01'],
            ['-0.01'],
            [ '1.002'],
            [ '0.001'],
            ['-1.002'],
            ['-0.001']
        ];
    }

    /**
     * @dataProvider providerToString
     *
     * @param string  $unscaledValue The unscaled value.
     * @param integer $scale         The scale.
     * @param string  $expected      The expected string representation.
     */
    public function testToString($unscaledValue, $scale, $expected)
    {
        $this->assertSame($expected, (string) BigDecimal::ofUnscaledValue($unscaledValue, $scale));
    }

    /**
     * @return array
     */
    public function providerToString()
    {
        return [
            ['0',   0, '0'],
            ['0',   1, '0.0'],
            ['1',   1, '0.1'],
            ['0',   2, '0.00'],
            ['1',   2, '0.01'],
            ['10',  2, '0.10'],
            ['11',  2, '0.11'],
            ['11',  3, '0.011'],
            ['1',   0, '1'],
            ['10',  1, '1.0'],
            ['11',  1, '1.1'],
            ['100', 2, '1.00'],
            ['101', 2, '1.01'],
            ['110', 2, '1.10'],
            ['111', 2, '1.11'],
            ['111', 3, '0.111'],
            ['111', 4, '0.0111'],

            ['-1',   1, '-0.1'],
            ['-1',   2, '-0.01'],
            ['-10',  2, '-0.10'],
            ['-11',  2, '-0.11'],
            ['-12',  3, '-0.012'],
            ['-12',  4, '-0.0012'],
            ['-1',   0, '-1'],
            ['-10',  1, '-1.0'],
            ['-12',  1, '-1.2'],
            ['-100', 2, '-1.00'],
            ['-101', 2, '-1.01'],
            ['-120', 2, '-1.20'],
            ['-123', 2, '-1.23'],
            ['-123', 3, '-0.123'],
            ['-123', 4, '-0.0123'],
        ];
    }
}
