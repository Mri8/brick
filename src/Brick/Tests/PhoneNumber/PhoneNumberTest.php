<?php

namespace Brick\Tests\PhoneNumber;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberType;
use Brick\PhoneNumber\PhoneNumberFormat;

/**
 * Test for class PhoneNumber.
 */
class PhoneNumberTest extends \PHPUnit_Framework_TestCase
{
    const ALPHA_NUMERIC_NUMBER = '+180074935247';
    const AE_UAN = '+971600123456';
    const AR_MOBILE = '+5491187654321';
    const AR_NUMBER = '+541187654321';
    const AU_NUMBER = '+61236618300';
    const BS_MOBILE = '+12423570000';
    const BS_NUMBER = '+12423651234';
    // Note that this is the same as the example number for DE in the metadata.
    const DE_NUMBER = '+4930123456';
    const DE_SHORT_NUMBER = '+491234';
    const GB_MOBILE = '+447912345678';
    const GB_NUMBER = '+442070313000';
    const IT_MOBILE = '+39345678901';
    const IT_NUMBER = '+390236618300';
    const JP_STAR_NUMBER = '+812345';
    // Numbers to test the formatting rules from Mexico.
    const MX_MOBILE1 = '+5212345678900';
    const MX_MOBILE2 = '+5215512345678';
    const MX_NUMBER1 = '+523312345678';
    const MX_NUMBER2 = '+528211234567';
    const NZ_NUMBER = '+6433316005';
    const SG_NUMBER = '+6565218000';
    // A too-long and hence invalid US number.
    const US_LONG_NUMBER = '+165025300001';
    const US_NUMBER = '+16502530000';
    const US_PREMIUM = '+19002530000';
    // Too short, but still possible US numbers.
    const US_LOCAL_NUMBER = '+12530000';
    const US_SHORT_BY_ONE_NUMBER = '+1650253000';
    const US_TOLLFREE = '+18002530000';
    const INTERNATIONAL_TOLL_FREE = '+80012345678';
    // We set this to be the same length as numbers for the other non-geographical country prefix that
    // we have in our test metadata. However, this is not considered valid because they differ in
    // their country calling code.
    const INTERNATIONAL_TOLL_FREE_TOO_LONG = '+800123456789';
    const UNIVERSAL_PREMIUM_RATE = '+979123456789';
    const UNKNOWN_COUNTRY_CODE_NO_RAW_INPUT = '+212345';

    /**
     * @dataProvider getNationalNumberProvider
     *
     * @param string $expectedNationalNumber
     * @param string $phoneNumber
     */
    public function testGetNationalNumber($expectedNationalNumber, $phoneNumber)
    {
        $this->assertEquals($expectedNationalNumber, PhoneNumber::parse($phoneNumber)->getNationalNumber());
    }

    /**
     * @return array
     */
    public function getNationalNumberProvider()
    {
        return [
            ['6502530000', self::US_NUMBER],
            ['345678901', self::IT_MOBILE],
            ['0236618300', self::IT_NUMBER],
            ['12345678', self::INTERNATIONAL_TOLL_FREE]
        ];
    }

    /**
     * @dataProvider parseNationalNumberProvider
     *
     * @param string $expectedNumber
     * @param string $numberToParse
     * @param string $regionCode
     */
    public function testParseNationalNumber($expectedNumber, $numberToParse, $regionCode)
    {
        $this->assertEquals(PhoneNumber::parse($expectedNumber), PhoneNumber::parse($numberToParse, $regionCode));
    }

    /**
     * @return array
     */
    public function parseNationalNumberProvider()
    {
        return [
            // National prefix attached.
            [self::NZ_NUMBER, '033316005', 'NZ'],
            [self::NZ_NUMBER, '33316005', 'NZ'],

            // National prefix attached and some formatting present.
            [self::NZ_NUMBER, '03-331 6005', 'NZ'],
            [self::NZ_NUMBER, '03 331 6005', 'NZ'],

            // Testing international prefixes.
            // Should strip country calling code.
            [self::NZ_NUMBER, '0064 3 331 6005', 'NZ'],

            // Try again, but this time we have an international number with Region Code US.
            // It should recognise the country calling code and parse accordingly.
            [self::NZ_NUMBER, '01164 3 331 6005', 'US'],
            [self::NZ_NUMBER, '+64 3 331 6005', 'US'],

// @todo
//            ['+6464123456', '64(0)64123456', 'NZ'],

            // Check that using a '/' is fine in a phone number.
            [self::DE_NUMBER, '301/23456', 'DE'],

            // Check it doesn't use the '1' as a country calling code
            // when parsing if the phone number was already possible.
            ['+11234567890', '123-456-7890', 'US']
        ];
    }

    /**
     * @dataProvider getRegionCodeProvider
     *
     * @param string $expectedRegion
     * @param string $phoneNumber
     */
    public function testGetRegionCode($expectedRegion, $phoneNumber)
    {
        $this->assertEquals($expectedRegion, PhoneNumber::parse($phoneNumber)->getRegionCode());
    }

    /**
     * @return array
     */
    public function getRegionCodeProvider()
    {
        return [
            ['BS', self::BS_NUMBER],
            ['US', self::US_NUMBER],
            ['GB', self::GB_MOBILE],
            [null, self::INTERNATIONAL_TOLL_FREE],
        ];
    }

    /**
     * @dataProvider getNumberTypeProvider
     *
     * @param string $numberType
     * @param string $phoneNumber
     */
    public function testGetNumberType($numberType, $phoneNumber)
    {
        $this->assertEquals($numberType, PhoneNumber::parse($phoneNumber)->getNumberType());
    }

    /**
     * @return array
     */
    public function getNumberTypeProvider()
    {
        return [
            [PhoneNumberType::PREMIUM_RATE, self::US_PREMIUM],
            [PhoneNumberType::PREMIUM_RATE, '+39892123'],
            [PhoneNumberType::PREMIUM_RATE, '+449187654321'],
            [PhoneNumberType::PREMIUM_RATE, '+499001654321'],
            [PhoneNumberType::PREMIUM_RATE, '+4990091234567'],
            [PhoneNumberType::PREMIUM_RATE, self::UNIVERSAL_PREMIUM_RATE],
// @todo doesn't work in online r557 either
//            [PhoneNumberType::TOLL_FREE, '+18881234567'],
            [PhoneNumberType::TOLL_FREE, '+39803123'],
// @todo doesn't work in online r557 either
//            [PhoneNumberType::TOLL_FREE, '+448012345678'],
            [PhoneNumberType::TOLL_FREE, '+498001234567'],
            [PhoneNumberType::TOLL_FREE, self::INTERNATIONAL_TOLL_FREE],

            [PhoneNumberType::MOBILE, self::BS_MOBILE],
            [PhoneNumberType::MOBILE, self::GB_MOBILE],
// @todo doesn't work in online r557 either
//            [PhoneNumberType::MOBILE, self::IT_MOBILE],
            [PhoneNumberType::MOBILE, self::AR_MOBILE],
// @todo this matches both fixedLine & mobile, but is still reported as MOBILE in the java version
//            [PhoneNumberType::MOBILE, '+4915123456789'],
// @todo doesn't work in online r557 either
//            [PhoneNumberType::MOBILE, self::MX_MOBILE1],
            [PhoneNumberType::MOBILE, self::MX_MOBILE2],

            [PhoneNumberType::FIXED_LINE, self::BS_NUMBER],
            [PhoneNumberType::FIXED_LINE, self::IT_NUMBER],
            [PhoneNumberType::FIXED_LINE, self::GB_NUMBER],
            [PhoneNumberType::FIXED_LINE, self::DE_NUMBER],

            [PhoneNumberType::FIXED_LINE_OR_MOBILE, self::US_NUMBER],
// @todo doesn't work in online r557 either
//            [PhoneNumberType::FIXED_LINE_OR_MOBILE, '+541987654321'],

            [PhoneNumberType::SHARED_COST, '+448431231234'],

            [PhoneNumberType::VOIP, '+445631231234'],

            [PhoneNumberType::PERSONAL_NUMBER, '+447031231234'],

            [PhoneNumberType::UNKNOWN, self::US_LOCAL_NUMBER]
        ];
    }

    /**
     * @dataProvider isValidNumberProvider
     *
     * @param string $phoneNumber
     */
    public function testIsValidNumber($phoneNumber)
    {
        $this->assertTrue(PhoneNumber::parse($phoneNumber)->isValidNumber());
    }

    /**
     * @return array
     */
    public function isValidNumberProvider()
    {
        return [
            [self::US_NUMBER],
            [self::IT_NUMBER],
            [self::GB_MOBILE],
            [self::INTERNATIONAL_TOLL_FREE],
            [self::UNIVERSAL_PREMIUM_RATE],
            ['+6421387835']
        ];
    }

    /**
     * @dataProvider isNotValidNumberProvider
     *
     * @param string $phoneNumber
     */
    public function testIsNotValidNumber($phoneNumber)
    {
        $this->assertFalse(PhoneNumber::parse($phoneNumber)->isValidNumber());
    }

    /**
     * @return array
     */
    public function isNotValidNumberProvider()
    {
        return [
            [self::US_LOCAL_NUMBER],
            ['+3923661830000'],
            ['+44791234567'],
            ['+491234'],
            ['+643316005'],
            ['+39232366'],
            [self::INTERNATIONAL_TOLL_FREE_TOO_LONG]
        ];
    }

    /**
     * @dataProvider parseExceptionProvider
     * @expectedException \Brick\PhoneNumber\PhoneNumberParseException
     *
     * @param string $phoneNumber
     * @param string|null $regionCode
     */
    public function testParseException($phoneNumber, $regionCode = null)
    {
        PhoneNumber::parse($phoneNumber, $regionCode);
    }

    /**
     * @return array
     */
    public function parseExceptionProvider()
    {
        return [
            // Empty string.
            [''],
            ['', 'US'],

            ['This is not a phone number', 'NZ'],
            ['1 Still not a number', 'NZ'],
            ['1 MICROSOFT', 'NZ'],
            ['12 MICROSOFT', 'NZ'],
            ['01495 72553301873 810104', 'GB'],
            ['+---', 'DE'],
            ['+***', 'DE'],
            ['+*******91', 'DE'],
            ['+ 00 210 3 331 6005', 'NZ'],

            // Too short.
            ['+49 0', 'DE'],

            // Does not match a country code.
            ['+02366'],
            ['+210 3456 56789', 'NZ'],

            // A region code must be given if not in international format.
            ['123 456 7890'],

            // Unknown region code (deprecated and removed from ISO 3166-2).
            ['123 456 7890', 'CS'],

            // No number, only region code.
            ['0044', 'GB'],
            ['0044------', 'GB'],

            // Only IDD provided.
            ['011', 'US'],

            // Only IDD and then 9.
            ['0119', 'US']
        ];
    }

    /**
     * @dataProvider formatNumberProvider
     *
     * @param string $expected
     * @param string $phoneNumber
     * @param string $numberFormat
     */
    public function testFormatNumber($expected, $phoneNumber, $numberFormat)
    {
        $this->assertEquals($expected, PhoneNumber::parse($phoneNumber)->format($numberFormat));
    }

    /**
     * @return array
     */
    public function formatNumberProvider()
    {
        return []; // @todo;

        return [
            // US
            ['(650) 253-0000', self::US_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+1 650-253-0000', self::US_NUMBER, PhoneNumberFormat::INTERNATIONAL],

            ['(800) 253-0000', self::US_TOLLFREE, PhoneNumberFormat::NATIONAL],
            ['+1 800-253-0000', self::US_TOLLFREE, PhoneNumberFormat::INTERNATIONAL],

            ['(900) 253-0000', self::US_PREMIUM, PhoneNumberFormat::NATIONAL],
            ['+1 900-253-0000', self::US_PREMIUM, PhoneNumberFormat::INTERNATIONAL],
// @todo
//            ['tel:+1-900-253-0000', self::US_PREMIUM, PhoneNumberFormat::RFC3966],

            // BS
            ['(242) 365-1234', self::BS_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+1 242-365-1234', self::BS_NUMBER, PhoneNumberFormat::INTERNATIONAL],

            // GB
            ['020 7031 3000', self::GB_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+44 20 7031 3000', self::GB_NUMBER, PhoneNumberFormat::INTERNATIONAL],

            ['07912 345678', self::GB_MOBILE, PhoneNumberFormat::NATIONAL],
            ['+44 7912 345678', self::GB_MOBILE, PhoneNumberFormat::INTERNATIONAL],

            // DE
            ['030 1234', '+49301234', PhoneNumberFormat::NATIONAL],
            ['+49 30 1234', '+49301234', PhoneNumberFormat::INTERNATIONAL],
            ['tel:+49-30-1234', '+49301234', PhoneNumberFormat::RFC3966],

            ['0291 123', '+49291123', PhoneNumberFormat::NATIONAL],
            ['+49 291 123', '+49291123', PhoneNumberFormat::INTERNATIONAL],

            ['0291 12345678', '+4929112345678', PhoneNumberFormat::NATIONAL],
            ['+49 291 12345678', '+4929112345678', PhoneNumberFormat::INTERNATIONAL],

            ['09123 12345', '+49912312345', PhoneNumberFormat::NATIONAL],
            ['+49 9123 12345', '+49912312345', PhoneNumberFormat::INTERNATIONAL],

            ['08021 2345', '+4980212345', PhoneNumberFormat::NATIONAL],
            ['+49 8021 2345', '+4980212345', PhoneNumberFormat::INTERNATIONAL],

            ['1234', self::DE_NUMBER, PhoneNumberFormat::NATIONAL],

            ['04134 1234', '+4941341234', PhoneNumberFormat::NATIONAL],

            // IT
            ['02 3661 8300', self::IT_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+39 02 3661 8300', self::IT_NUMBER, PhoneNumberFormat::INTERNATIONAL],
            ['+390236618300', self::IT_NUMBER, PhoneNumberFormat::E164],

            ['345 678 901', self::IT_MOBILE, PhoneNumberFormat::NATIONAL],
            ['+39 345 678 901', self::IT_MOBILE, PhoneNumberFormat::INTERNATIONAL],
            ['+39345678901', self::IT_MOBILE, PhoneNumberFormat::E164],

            // AU
            ['(02) 3661 8300', self::AU_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+61 2 3661 8300', self::AU_NUMBER, PhoneNumberFormat::INTERNATIONAL],
            ['+61236618300', self::AU_NUMBER, PhoneNumberFormat::E164],

            ['1800 123 456', '+611800123456', PhoneNumberFormat::NATIONAL],
            ['+61 1800 123 456', '+611800123456', PhoneNumberFormat::INTERNATIONAL],
            ['+611800123456', '+611800123456', PhoneNumberFormat::E164],

            // AR
            ['011 8765-4321', self::AR_NUMBER, PhoneNumberFormat::NATIONAL],
            ['+54 11 8765-4321', self::AR_NUMBER, PhoneNumberFormat::INTERNATIONAL],
            ['+541187654321', self::AR_NUMBER, PhoneNumberFormat::E164],

            ['011 15-8765-4321', self::AR_MOBILE, PhoneNumberFormat::NATIONAL],
            ['+54 9 11 8765-4321', self::AR_MOBILE, PhoneNumberFormat::INTERNATIONAL],
            ['+5491187654321', self::AR_MOBILE, PhoneNumberFormat::E164],

            // MX
            ['045 234 567 8900', self::MX_MOBILE1, PhoneNumberFormat::NATIONAL],
            ['+52 1 234 567 8900', self::MX_MOBILE1, PhoneNumberFormat::INTERNATIONAL],
            ['+5212345678900', self::MX_MOBILE1, PhoneNumberFormat::E164],

            ['044 55 1234 5678', self::MX_MOBILE2, PhoneNumberFormat::NATIONAL],
            ['+52 1 55 1234 5678', self::MX_MOBILE2, PhoneNumberFormat::INTERNATIONAL],
            ['+5215512345678', self::MX_MOBILE2, PhoneNumberFormat::E164],

            ['01 33 1234 5678', self::MX_NUMBER1, PhoneNumberFormat::NATIONAL],
            ['+52 33 1234 5678', self::MX_NUMBER1, PhoneNumberFormat::INTERNATIONAL],
            ['+523312345678', self::MX_NUMBER1, PhoneNumberFormat::E164],

            ['01 821 123 4567', self::MX_NUMBER2, PhoneNumberFormat::NATIONAL],
            ['+52 821 123 4567', self::MX_NUMBER2, PhoneNumberFormat::INTERNATIONAL],
            ['+528211234567', self::MX_NUMBER2, PhoneNumberFormat::E164]
        ];
    }
}
