<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'AU',
   'countryCode' => '61',
   'mainCountryForCode' => 'true',
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '0011',
   'internationalPrefix' => '(?:14(?:1[14]|34|4[17]|[56]6|7[47]|88))?001[14-689]',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '($NP$FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([2378])(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2378]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[45]|14',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(16)(\\d{3})(\\d{2,4})',
       'leadingDigits' => 
      array (
        0 => '16',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1[389]\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '1(?:[38]0|90)',
        1 => '1(?:[38]00|90)',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(180)(2\\d{3})',
       'leadingDigits' => 
      array (
        0 => '180',
        1 => '1802',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(19\\d)(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '19[13]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(19\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '19[67]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(13)(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '13[1-9]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-578]\\d{5,9}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:3(?:\\d{4}|00\\d{6})|80(?:0\\d{6}|2\\d{3}))',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '1300123456',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[237]\\d{8}|8(?:[68]\\d{3}|7[0-69]\\d{2}|9(?:[02-9]\\d{2}|1(?:[0-57-9]\\d|6[0135-9])))\\d{4}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => '212345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '14(?:5\\d|71)\\d{5}|4(?:[0-2]\\d|3[0-57-9]|4[47-9]|5[0-35-9]|6[6-9]|[79][07-9]|8[17-9])\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '412345678',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '16\\d{3,7}',
     'possibleNumberPattern' => '\\d{5,9}',
     'exampleNumber' => '1612345',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '180(?:0\\d{3}|2)\\d{3}',
     'possibleNumberPattern' => '\\d{7,10}',
     'exampleNumber' => '1800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '19(?:0[0126]\\d{6}|[13-5]\\d{3}|[679]\\d{5})',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '1900123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '13(?:00\\d{2})?\\d{4}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '1300123456',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '500\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '500123456',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '550\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '550123456',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '000|112',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));