#import "PHPFunctions.h"
#import "RootSolver.h"
#import "PHPUndefined.h"

@implementation Evaluation
- (void )initializeItems {
[self setMath:[[Math alloc] init]];
}
- (NSObject* )makeIntoStr: (NSObject* ) value  {
if([[self itemIsArray:value] boolValue]) {
NSMutableDictionary*  valueDict = value;
valueDict[@"value"]=[self makeIntoStr:valueDict[@"value"]];
return valueDict;

}
NSNumber*  iskind = @([value isKindOfClass:[NSString class]]);
if([iskind boolValue]) {
return value;

}
NSNumber*  valueNum = value;
return [valueNum stringValue];
}
- (NSMutableArray* )arrayUnique: (NSMutableArray* ) arr  {
return [[NSSet setWithArray:arr] allObjects];
}
- (NSMutableArray* )concat: (NSMutableArray* ) a b: (NSMutableArray* ) b  {
return [a arrayByAddingObjectsFromArray:b];
}
- (NSNumber* )itemIsArray: (NSObject* ) item  {
NSNumber*  iskind = @([item isKindOfClass:[NSMutableArray class]]);
if([iskind boolValue]) {
return @true;

}
iskind = @([item isKindOfClass:[NSMutableDictionary class]]);
if([iskind boolValue]) {
return @true;

}
return @false;
}
- (NSNumber* )inArray: (NSObject* ) item arr: (NSMutableArray* ) arr  {
NSNumber*  contains = @([arr containsObject:item]);
if([contains boolValue]) {
return @true;

}
return @false;
}
- (NSMutableArray* )strSplit: (NSString* ) value  {
NSNumber*  valueNum = value;
NSNumber*  iskind = @([valueNum isKindOfClass:[NSString class]]);
if(![iskind boolValue]) {
value = [valueNum stringValue];

}
NSMutableArray*  results = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  i = @0;
while([i isLessThan:@([value length])])
{
[results addObject:[NSString stringWithFormat:@"%C" ,[value characterAtIndex:[i longLongValue]]]];
i = @([i longLongValue]+1);

}
return results;
}
- (NSMutableArray* )explode: (NSString* ) delimiter term: (NSString* ) term  {
return [[NSMutableArray alloc] initWithArray:[term componentsSeparatedByString:delimiter]];
}
- (NSMutableArray* )split: (NSString* ) delimiter term: (NSString* ) term  {
return [self explode:delimiter  term: term];
}
- (NSString* )substr: (NSString* ) value start: (NSNumber* ) start length: (NSNumber* ) length  {
if([@([value length]) isEqualTo:@0]) {
return @"";

}
if(length == nil) {
length = @([value length]);

}
length = @([length doubleValue]-[start doubleValue]);
NSRange range = NSMakeRange([start intValue],[length intValue]);
return [value substringWithRange:range];
}
- (NSNumber* )isset: (NSObject* ) value  {
NSNumber*  iskindUndefined = @([value isKindOfClass:[PHPUndefined class]]);
if(value == nil||value == NULL||[iskindUndefined boolValue]) {
return @false;

}
return @true;
}
- (NSNumber* )issetAlt: (NSMutableArray* ) arr key: (NSNumber* ) key  {
if([@([arr count]) isGreaterThan:key]) {
return @true;

}
return @false;
}
- (NSNumber* )strlen: (NSString* ) value  {
return @([value length]);
}
- (NSString* )strrev: (NSString* ) value  {
return [self implode:@""  term:[[NSMutableArray alloc] initWithArray:[[[self strSplit:value] reverseObjectEnumerator] allObjects]]];
}
- (NSString* )trimSub: (NSString* ) value  {
NSString*  trimmed = [value stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
return trimmed;
}
- (NSNumber* )strpos: (NSString* ) value search: (NSString* ) search  {
NSRange range = [value rangeOfString:search];
NSNumber*  notFound = @(NSNotFound);
if([@(range.location) isEqualTo:notFound]) {
return (@(-[@1 doubleValue]));

}
return @(range.location);
}
- (NSObject* )arrayShift: (NSMutableArray* ) arr  {
if([@([arr count]) isEqualTo:@0]) {
return NULL;

}
NSObject*  result = [arr firstObject];
NSNumber*  zero = @0;
[arr removeObjectAtIndex:[zero longLongValue]];
return result;
}
- (NSObject* )arrayPop: (NSMutableArray* ) arr  {
if([@([arr count]) isEqualTo:@0]) {
return NULL;

}
NSObject*  result = [arr lastObject];
NSNumber*  index = @([@([arr count]) doubleValue]-[@1 doubleValue]);
[arr removeObjectAtIndex:[index longLongValue]];
return result;
}
- (void )arrayUnshift: (NSMutableArray* ) arr item: (NSObject* ) item  {
NSNumber*  zero = @0;
[arr insertObject:item atIndex:[zero longLongValue]];
}
- (NSString* )join: (NSString* ) delimiter term: (NSMutableArray* ) term  {
NSNumber*  counter = @0;
NSString*  result = @"";
for(NSString*  termItem in term) {
if([counter isGreaterThan:@0]) {
result = [result stringByAppendingString:delimiter];

}
result = [result stringByAppendingString:termItem];
counter = @([counter longLongValue]+1);

}
return result;
}
- (NSString* )implode: (NSString* ) delimiter term: (NSMutableArray* ) term  {
return [self join:delimiter term:term];
}
- (NSMutableArray* )reverse: (NSMutableArray* ) input  {
NSMutableArray*  results = [[NSMutableArray alloc] initWithArray:[[input reverseObjectEnumerator] allObjects]];
return results;
}
- (NSMutableArray* )arrayReverse: (NSMutableArray* ) input  {
return [self reverse:input];
}
- (NSNumber* )countvalue: (NSObject* ) input  {
NSNumber*  iskind = @([input isKindOfClass:[NSMutableArray class]]);
if([iskind boolValue]) {
NSMutableArray*  arr = input;
return @([arr count]);

}
NSMutableDictionary*  arrdict = input;
return @([[arrdict allValues] count]);
}
- (NSMutableArray* )getDigits: (NSString* ) term removeDecimalPoint: (NSNumber* ) removeDecimalPoint removeNegative: (NSNumber* ) removeNegative  {
if(removeDecimalPoint == nil) {
removeDecimalPoint = @true;

}
if(removeNegative == nil) {
removeNegative = @true;

}
if([removeDecimalPoint boolValue]) {
term = [self explode:@"." term:term];
term = [self implode:@"" term:term];

}
NSMutableArray*  digits = [self strSplit:term];
digits = [self reverse:digits];
return digits;
}
- (NSString* )removeLeadingZeros: (NSString* ) value reverse: (NSNumber* ) reverse  {
if(reverse == nil) {
reverse = @false;

}
NSMutableArray*  digits = [self strSplit:value];
if([reverse boolValue]) {
digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];

}
NSNumber*  counter = @0;
NSNumber*  nonZero = @false;
NSString*  result = @"";
NSNumber*  zeroCount = @0;
counter = @0;
for(NSString*  digit in digits) {
if(![nonZero boolValue]) {
if(![digit isEqualTo:@"0"]) {
nonZero = @true;
result = [result stringByAppendingString:digit];

}else {
zeroCount = @([zeroCount longLongValue]+1);

}

}else {
result = [result stringByAppendingString:digit];

}
counter = @([counter longLongValue]+1);

}
if([reverse boolValue]) {
result = [self strrev:result];

}
if([[self trim:result] isEqualTo:@""]) {
result = @"0";

}
return result;
}
- (NSString* )result: (NSString* ) termA termB: (NSString* ) termB  {
NSNumber*  aIskind = @([termA isKindOfClass:[NSNumber class]]);
NSNumber*  bIskind = @([termB isKindOfClass:[NSNumber class]]);
if([aIskind boolValue]) {
NSNumber*  aNum = termA;
termA = [aNum stringValue];

}
if([bIskind boolValue]) {
NSNumber*  bNum = termB;
termB = [bNum stringValue];

}
NSNumber*  negative = @false;
if(([[self negative:termA] boolValue] && ![[self negative:termB] boolValue])||([[self negative:termB] boolValue] && ![[self negative:termA] boolValue])) {
negative = @true;

}
NSString*  result = [self resultSub:[self absolute:termA] termB:[self absolute:termB]];
if([negative boolValue]) {
result = [@"-" stringByAppendingString:result];

}
return result;
}
- (NSString* )resultSub: (NSString* ) termA termB: (NSString* ) termB  {
NSMutableArray*  intermediateResults = [[NSMutableArray alloc] initWithArray:@[]];
if([termA isEqualTo:@0]||[termB isEqualTo:@0]) {
return @0;

}
NSMutableArray*  aDigits = [self getDigits:termA  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  bDigits = [self getDigits:termB  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  startStop = @false;
NSNumber*  exponentB = @0;
for(NSString*  valueB in bDigits) {
if(![valueB isEqualTo:@0]) {
NSNumber*  exponentA = @0;
for(NSString*  valueA in aDigits) {
if(![valueA isEqualTo:@0]) {
NSNumber*  multA = @([valueA intValue]);
NSNumber*  multB = @([valueB intValue]);
NSNumber*  value = [[self math] mult:multA  b: multB];
NSString*  valueValue = [value stringValue];
NSNumber*  exponent = @([exponentA doubleValue]+[exponentB doubleValue]);
NSString*  exponentValue = [exponent stringValue];
[intermediateResults addObject:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":valueValue, @"exponent":exponentValue}]];

}
exponentA = @([exponentA longLongValue]+1);

}

}
exponentB = @([exponentB longLongValue]+1);

}
NSString*  result = NULL;
for(NSMutableDictionary*  resultValueItem in intermediateResults) {
NSString*  resultValue = [self numericValue:resultValueItem];
if(result == NULL) {
result = resultValue;

}else {
result = [self add:result  termB: resultValue];

}

}
return result;
}
- (NSString* )addSub: (NSString* ) termA termB: (NSString* ) termB base: (NSNumber* ) base limitDecimals: (NSNumber* ) limitDecimals  {
if(base == nil) {
base = @10;

}
if(limitDecimals == nil) {
limitDecimals = @false;

}
termA = [self absolute:termA];
termB = [self absolute:termB];
NSNumber*  decimalPoint = (@(-[@1 doubleValue]));
if(![[self strpos:termA search:@"."] isEqualTo:(@(-[@1 doubleValue]))]||![[self strpos:termB search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableDictionary*  terms = [self synchronizeValues:termA termB:termB];
termA = terms[@"a"];
termB = terms[@"b"];
decimalPoint = terms[@"fractionLength"];

}
NSMutableArray*  aDigits = [self getDigits:termA  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  bDigits = [self getDigits:termB  removeDecimalPoint:nil removeNegative:nil];
if([[self countvalue:bDigits] isGreaterThan:[self countvalue:aDigits]]) {
NSMutableArray*  switchValue = aDigits;
aDigits = bDigits;
bDigits = switchValue;

}
NSMutableArray*  returnDigits = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  carryValue = NULL;
NSNumber*  keyA = @0;
for(NSString*  aDigitItem in aDigits) {
NSNumber*  addition = @0;
NSString*  aDigit = aDigitItem;
if([@([bDigits count]) isGreaterThan:keyA]) {
NSString*  bDigit = bDigits[[keyA longLongValue]];
NSNumber*  aDigitNumeric = @([aDigit intValue]);
NSNumber*  bDigitNumeric = @([bDigit intValue]);
addition = @([aDigitNumeric doubleValue]+[bDigitNumeric doubleValue]);

}else {
addition = @([aDigit intValue]);

}
if(carryValue != NULL && ![carryValue isEqualTo:@""]) {
addition = @([addition doubleValue]+[carryValue doubleValue]);
carryValue = NULL;

}
if(![limitDecimals boolValue]) {
if((([addition isGreaterThanOrEqualTo:@10] && [keyA isGreaterThan:@0])||([base isEqualTo:@10] && [addition isGreaterThanOrEqualTo:@10]))) {
NSMutableArray*  additionDigits = [self strSplit:[addition stringValue]];
carryValue = @([additionDigits[0] intValue]);
addition = @([additionDigits[1] intValue]);

}else if([addition isGreaterThanOrEqualTo:base] && ([keyA isEqualTo:@0])) {
carryValue = @1;
addition = @([addition doubleValue]-[base doubleValue]);

}

}else {
if([addition isGreaterThanOrEqualTo:base]) {
carryValue = @1;
addition = @([addition doubleValue]-[base doubleValue]);

}

}
[returnDigits addObject:[addition stringValue]];
keyA = @([keyA longLongValue]+1);

}
if(carryValue != NULL) {
[returnDigits addObject:[carryValue stringValue]];

}
returnDigits = [self reverse:returnDigits];
NSString*  value = [self implode:@"" term:returnDigits];
if(![decimalPoint isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
value = @"";
NSNumber*  key = @0;
for(NSString*  digit in digits) {
if([key isEqualTo:decimalPoint]) {
value = [digit stringByAppendingString:[@"." stringByAppendingString:value]];

}else {
value = [digit stringByAppendingString:value];

}
key = @([key longLongValue]+1);

}

}
if([limitDecimals boolValue]) {
NSNumber*  reAdd = @false;
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  key = @0;
for(NSString*  digit in digits) {
NSNumber*  digitNumeric = @([digit intValue]);
if([digitNumeric isGreaterThanOrEqualTo:base]) {
reAdd = @true;

}
key = @([key longLongValue]+1);

}
if([reAdd boolValue]) {
value = [self addSub:value termB:@"0" base:base limitDecimals:@true];

}

}
return value;
}
- (NSString* )add: (NSString* ) termA termB: (NSString* ) termB  {
NSNumber*  aIskind = @([termA isKindOfClass:[NSNumber class]]);
NSNumber*  bIskind = @([termB isKindOfClass:[NSNumber class]]);
if([aIskind boolValue]) {
NSNumber*  aNum = termA;
termA = [aNum stringValue];

}
if([bIskind boolValue]) {
NSNumber*  bNum = termB;
termB = [bNum stringValue];

}
if([[self negative:termA] boolValue] && [[self negative:termB] boolValue]) {
return [@"-" stringByAppendingString:[self addSub:[self absolute:termA] termB:[self absolute:termB] base:nil  limitDecimals:nil]];

}else if([[self negative:termA] boolValue] && ![[self negative:termB] boolValue]) {
return [self subtract:[self absolute:termB] termB:[self absolute:termA]];

}else if(![[self negative:termA] boolValue] && [[self negative:termB] boolValue]) {
return [self subtract:[self absolute:termA] termB:[self absolute:termB]];

}else {
return [self addSub:[self absolute:termA] termB:[self absolute:termB] base:nil  limitDecimals:nil];

}
}
- (NSString* )addMultiple: (NSMutableArray* ) values  {
NSString*  result = @"0";
for(NSString*  value in values) {
result = [self add:result termB:value];

}
return result;
}
- (NSString* )removeMinus: (NSString* ) value  {
if(![[self strpos:value search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"-" term:value];
value = split[1];

}
return value;
}
- (NSString* )subtractSub: (NSString* ) termA termB: (NSString* ) termB base: (NSNumber* ) base limitDecimals: (NSNumber* ) limitDecimals  {
if(limitDecimals == nil) {
limitDecimals = @false;

}
NSNumber*  decimalPoint = @(-[@1 doubleValue]);
if(![[self strpos:termA search:@"."] isEqualTo:(@(-[@1 doubleValue]))]||![[self strpos:termB search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableDictionary*  terms = [self synchronizeValues:termA termB:termB];
termA = terms[@"a"];
termB = terms[@"b"];
decimalPoint = terms[@"fractionLength"];

}
NSMutableArray*  aDigits = [self getDigits:termA  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  bDigits = [self getDigits:termB  removeDecimalPoint:nil removeNegative:nil];
NSString*  minusSign = @"";
if([[self larger:termB valueB:termA equal:@true] boolValue] && ![termB isEqualTo:termA]) {
NSMutableArray*  switchValue = aDigits;
aDigits = bDigits;
bDigits = switchValue;
minusSign = @"-";

}
NSMutableArray*  returnDigits = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  carryValue = NULL;
NSMutableArray*  carryIndex = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  keyA = @0;
for(NSString*  aDigitStr in aDigits) {
NSNumber*  aDigit = @([aDigitStr intValue]);
NSNumber*  addition = @0;
if([@([bDigits count]) isGreaterThan:keyA]) {
if([aDigit isEqualTo:@""]) {
aDigit = @0;

}
NSString*  bDigitStr = bDigits[[keyA longLongValue]];
NSNumber*  bDigit = @([bDigitStr intValue]);
if([bDigit isEqualTo:@""]) {
bDigit = @0;

}
addition = @([aDigit doubleValue]-[bDigit doubleValue]);

}else {
addition = aDigit;

}
if(carryValue != NULL) {
addition = @([addition doubleValue]-[carryValue doubleValue]);
carryValue = NULL;

}
if([addition isLessThan:@0]) {
carryValue = @1;
addition = [self removeMinus:[addition stringValue]];
addition = @([addition intValue]);
addition = @([@10 doubleValue]-[addition doubleValue]);

}
[returnDigits addObject:[addition stringValue]];
keyA = @([keyA longLongValue]+1);

}
returnDigits = [self reverse:returnDigits];
NSString*  result = [self implode:@"" term:returnDigits];
result = [self removeLeadingZeros:result  reverse:nil];
if(![decimalPoint isEqualTo:(@(-[@1 doubleValue]))]) {
result = [self placeDecimal:result length:decimalPoint removeDecimal:@true prefix:@true];

}
result = [minusSign stringByAppendingString:result];
if([result isEqualTo:@""]) {
result = @0;

}
result = [self removeLeadingZeros:result  reverse:nil];
return result;
}
- (NSString* )subtract: (NSString* ) termA termB: (NSString* ) termB  {
NSNumber*  aIskind = @([termA isKindOfClass:[NSNumber class]]);
NSNumber*  bIskind = @([termB isKindOfClass:[NSNumber class]]);
if([aIskind boolValue]) {
NSNumber*  aNum = termA;
termA = [aNum stringValue];

}
if([bIskind boolValue]) {
NSNumber*  bNum = termB;
termB = [bNum stringValue];

}
if(![[self strpos:termA search:@"-"] isEqualTo:(@(-[@1 doubleValue]))] && ![[self strpos:termB search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
termA = [self explode:@"-" term:termA][1];
termB = [self explode:@"-" term:termB][1];
return [self negativeValue:[self subtractSub:termB termB:termA  base:nil limitDecimals:nil]];

}else if([[self strpos:termA search:@"-"] isEqualTo:(@(-[@1 doubleValue]))] && ![[self strpos:termB search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
termB = [self explode:@"-" term:termB][1];
return [self add:termA termB:termB];

}else if(![[self strpos:termA search:@"-"] isEqualTo:(@(-[@1 doubleValue]))] && [[self strpos:termB search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
termA = [self explode:@"-" term:termA][1];
return [@"-" stringByAppendingString:[self add:termA termB:termB]];

}else {
return [self subtractSub:termA termB:termB  base:nil limitDecimals:nil];

}
}
- (NSString* )lengthenFraction: (NSString* ) value length: (NSString* ) length  {
NSMutableArray*  fraction = [self explode:@"/" term:value];
NSString*  numerator = [self result:length termB:fraction[0]];
NSString*  denominator = [self result:length termB:fraction[1]];
return [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
}
- (NSString* )lengthenTo: (NSString* ) value lengthTo: (NSString* ) lengthTo  {
NSMutableArray*  fraction = [self fractionValues:value];
NSString*  denominator = fraction[1];
NSMutableDictionary*  fractionTranslation = [self executeDivide:lengthTo divider:denominator shorten:@false fast:@false numeric:@false preShorten:@false absolute:@false];
fractionTranslation = fractionTranslation[@"value"];
return [self lengthenFraction:[self fractionString:fraction] length:fractionTranslation];
}
- (NSMutableDictionary* )common: (NSString* ) value shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
NSNumber*  decimalPoint = [self strpos:value search:@"."];
NSString*  assembly = value;
if(![decimalPoint isEqualTo:(@(-[@1 doubleValue]))]) {
NSNumber*  length = [self strlen:value];
NSMutableArray*  split = [self explode:@"." term:value];
assembly = [self removeLeadingZeros:[split[0] stringByAppendingString:split[1]] reverse:@false];
NSNumber*  denominatorDecimals = @([length doubleValue]-[decimalPoint doubleValue]);
NSString*  denominator = [self makeDecimalValue:denominatorDecimals];
assembly = [assembly stringByAppendingString:[@"/" stringByAppendingString:denominator]];

}
if([shorten boolValue]) {
assembly = [self executeShortenFraction:assembly bypassTruncation:nil];

}
return assembly;
}
- (NSString* )makeDecimalValue: (NSNumber* ) length  {
NSNumber*  counter = @1;
NSString*  returnValue = @"";
while([counter isLessThan:length])
{
returnValue = [returnValue stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}
returnValue = [@"1" stringByAppendingString:returnValue];
return returnValue;
}
- (NSNumber* )fraction: (NSString* ) value  {
if(![[self strpos:value search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
return @true;

}
return @false;
}
- (NSString* )multiplyFraction: (NSString* ) valueA valueB: (NSString* ) valueB shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
if([valueA isEqualTo:@""]||[valueB isEqualTo:@""]||valueA == NULL||valueB == NULL) {
return @"0/1";

}
NSMutableArray*  fractionA = [self fractionValues:valueA];
NSMutableArray*  fractionB = [[NSMutableArray alloc] initWithArray:@[]];
if(![[self strpos:valueB search:@"/"] isEqualTo:(@(-[@1 doubleValue]))]) {
fractionB = [self fractionValues:valueB];

}else {
fractionB = [[NSMutableArray alloc] initWithArray:@[valueB, valueB]];

}
if([fractionA[0] isEqualTo:@0]||[fractionB[0] isEqualTo:@0]) {
return @"0/1";

}
NSString*  numerator = [self result:fractionA[0] termB:fractionB[0]];
NSString*  denominator = [self result:fractionA[1] termB:fractionB[1]];
NSString*  result = [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
return result;
}
- (NSMutableDictionary* )multiplyTotal: (NSMutableDictionary* ) valueA valueB: (NSMutableDictionary* ) valueB shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
NSNumber*  negativeResult = @false;
if([[self negative:valueA] boolValue] && ![[self negative:valueB] boolValue]) {
negativeResult = @true;

}
if(![[self negative:valueA] boolValue] && [[self negative:valueB] boolValue]) {
negativeResult = @true;

}
valueA = [self absolute:valueA];
valueB = [self absolute:valueB];
NSMutableDictionary*  result = [self multiplyTotalSub:valueB value:valueA[@"value"]];
NSMutableDictionary*  multiplication = [self multiplyTotalSub:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":valueA[@"remainder"]}] value:valueB[@"value"]];
result = [self addTotal:result termB:multiplication shorten:nil];
result = [self addTotal:result termB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":[self multiplyFraction:valueA[@"remainder"] valueB: valueB[@"remainder"] shorten:nil]}] shorten:nil];
if([shorten boolValue]) {
result[@"remainder"]=[self executeShortenFraction:result[@"remainder"] bypassTruncation:nil];

}
if([negativeResult boolValue]) {
result = [self negativeValue:result];

}
result = [self cleanRemainder:result];
return result;
}
- (NSMutableDictionary* )multiplyTotalSub: (NSMutableDictionary* ) valueA value: (NSString* ) value  {
NSString*  result = [self result:valueA[@"value"] termB: value];
NSString*  fraction = [self multiplyFraction:valueA[@"remainder"] valueB: ([value stringByAppendingString:@"/1"])  shorten:nil];
NSMutableArray*  fractionValues = [self fractionValues:fraction];
NSMutableDictionary*  division = [self executeDivide:fractionValues[0] divider: fractionValues[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
if([[self larger:division[@"value"] valueB: @0  equal:nil] boolValue]) {
result = [self add:result  termB: division[@"value"]];
fraction = division[@"remainder"];

}
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":result, @"remainder":fraction}];
}
- (NSMutableDictionary* )multiplyTotalSubDepr: (NSMutableDictionary* ) valueTotal value: (NSString* ) value  {
NSString*  baseValue = [self result:valueTotal[@"value"] termB:value];
NSMutableArray*  fractionValues = [self fractionValues:valueTotal[@"remainder"]];
NSString*  t = [self result:fractionValues[0] termB:value];
NSMutableDictionary*  tDivision = [self executeDivide:t divider:fractionValues[1] shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil];
tDivision[@"value"]=[self add:baseValue termB:tDivision[@"value"]];
return t;
}
- (NSString* )minimizeFraction: (NSString* ) value  {
NSMutableArray*  fraction = [self fractionValues:value];
NSString*  numerator = fraction[0];
NSString*  denominator = fraction[1];
NSMutableArray*  numeratorDigits = [self getDigits:numerator  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  denominatorDigits = [self getDigits:denominator  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  numeratorNonZeroPoint = (@(-[@1 doubleValue]));
NSNumber*  denominatorNonZeroPoint = (@(-[@1 doubleValue]));
NSNumber*  key = @0;
for(NSString*  value in numeratorDigits) {
if(![value isEqualTo:@"0"] && [numeratorNonZeroPoint isEqualTo:(@(-[@1 doubleValue]))]) {
numeratorNonZeroPoint = key;

}
key = @([key longLongValue]+1);

}
key = @0;
for(NSString*  value in denominatorDigits) {
if(![value isEqualTo:@"0"] && [denominatorNonZeroPoint isEqualTo:(@(-[@1 doubleValue]))]) {
denominatorNonZeroPoint = key;

}
key = @([key longLongValue]+1);

}
NSNumber*  cutoff = NULL;
if([numeratorNonZeroPoint isLessThan:denominatorNonZeroPoint]) {
cutoff = numeratorNonZeroPoint;

}else {
cutoff = denominatorNonZeroPoint;

}
numerator = @"";
denominator = @"";
key = @0;
for(NSString*  value in numeratorDigits) {
if([key isGreaterThanOrEqualTo:cutoff]) {
numerator = [value stringByAppendingString:numerator];

}
key = @([key longLongValue]+1);

}
key = @0;
for(NSString*  value in denominatorDigits) {
if([key isGreaterThanOrEqualTo:cutoff]) {
denominator = [value stringByAppendingString:denominator];

}
key = @([key longLongValue]+1);

}
return [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
}
- (NSString* )ceil: (NSMutableDictionary* ) value  {
if([[self negative:value] boolValue]) {
return [self negativeValue:[self floor:[self absolute:value]]];

}
if(![[self fractionValues:value[@"remainder"]][0] isEqualTo:@0]) {
return [self add:value[@"value"] termB: @1];

}
return value[@"value"];
}
- (NSString* )round: (NSMutableDictionary* ) value  {
if([[self fractionValues:value[@"remainder"]][0] isEqualTo:@0]) {
return value[@"value"];

}
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
NSString*  numerator = fractionValues[0];
NSString*  denominator = fractionValues[1];
numerator = [self result:numerator termB:@2];
if([[self larger:numerator valueB:denominator equal:nil] boolValue]) {
return [self add:value[@"value"] termB:@1];

}
return value[@"value"];
}
- (void )assignTruncateFractions: (NSNumber* ) length  {
if(![length isEqualTo:@false] && length != NULL && [length isGreaterThan:@0]) {
[self setTruncateFractions:@true];
[self setTruncateFractionsLength:length];

}else {
[self setTruncateFractions:@false];

}
}
- (NSString* )stringPrefix: (NSNumber* ) depth  {
NSNumber*  counter = @0;
NSString*  results = @"-";
while([counter isLessThanOrEqualTo:depth])
{
results = [results stringByAppendingString:@"-"];
counter = @([counter longLongValue]+1);

}
return results;
}
- (NSMutableArray* )commonDenominator: (NSString* ) valueA valueB: (NSString* ) valueB  {
NSMutableArray*  fractionValuesA = [self fractionValues:valueA];
NSMutableArray*  fractionValuesB = [self fractionValues:valueB];
if([fractionValuesA[0] isEqualTo:@"0"]) {
return [[NSMutableArray alloc] initWithArray:@[[@"0/" stringByAppendingString:fractionValuesB[1]], valueB]];

}else if([fractionValuesB[0] isEqualTo:@"0"]) {
return [[NSMutableArray alloc] initWithArray:@[valueA,[@"0/" stringByAppendingString:fractionValuesA[1]]]];

}
NSString*  denominator = [self result:fractionValuesA[1] termB:fractionValuesB[1]];
NSString*  resultA = [[self result:fractionValuesB[1] termB:fractionValuesA[0]] stringByAppendingString:[@"/" stringByAppendingString:denominator]];
NSString*  resultB = [[self result:fractionValuesA[1] termB:fractionValuesB[0]] stringByAppendingString:[@"/" stringByAppendingString:denominator]];
return [[NSMutableArray alloc] initWithArray:@[resultA, resultB]];
}
- (NSMutableArray* )multipleDenominators: (NSMutableArray* ) values  {
NSMutableArray*  curCommon = NULL;
NSNumber*  key = @0;
for(NSString*  value in values) {
if([key isGreaterThan:@0]) {
if(curCommon == NULL) {
NSNumber*  keyValue = @([key doubleValue]-[@1 doubleValue]);
curCommon = [self commonDenominator:value valueB:values[[keyValue longLongValue]]];

}else {
curCommon = [self commonDenominator:value valueB:curCommon[0]];

}

}
key = @([key longLongValue]+1);

}
key = @0;
for(NSString*  value in values) {
NSMutableArray*  fraction = [self fractionValues:value];
NSMutableArray*  commonFraction = [self fractionValues:curCommon[0]];
NSString*  multiplier = [self executeDivide:commonFraction[1] divider:fraction[1] shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil][@"value"];
NSString*  result = [[self result:fraction[0] termB:multiplier] stringByAppendingString:[@"/" stringByAppendingString:[self result:fraction[1] termB:multiplier]]];
values[[key longLongValue]]=result;
key = @([key longLongValue]+1);

}
return values;
}
- (NSMutableArray* )fractionValues: (NSString* ) value  {
return [self explode:@"/"  term: value];
}
- (NSString* )fractionString: (NSMutableArray* ) fraction  {
return [fraction[0] stringByAppendingString:[@"/" stringByAppendingString:fraction[1]]];
}
- (NSString* )collectResults: (NSMutableArray* ) intermediateResults base: (NSNumber* ) base  {
if(base == nil) {
base = @10;

}
NSString*  result = @"0";
for(NSMutableDictionary*  resultValue in intermediateResults) {
NSString*  resultValueStr = [self numericValue:resultValue];
result = [self add:result termB:resultValueStr];

}
return result;
}
- (NSString* )placeDecimal: (NSString* ) value length: (NSNumber* ) length removeDecimal: (NSNumber* ) removeDecimal prefix: (NSNumber* ) prefix  {
if(removeDecimal == nil) {
removeDecimal = @false;

}
if(prefix == nil) {
prefix = @false;

}
NSNumber*  originalLengthSet = length;
if([removeDecimal boolValue] && ![[self strpos:value search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"." term:value];
value = [split[0] stringByAppendingString:split[1]];
NSNumber*  startOffset = [self strlen:split[0]];
length = (@([[self strlen:value] doubleValue]-[(@([startOffset doubleValue]+[length doubleValue])) doubleValue]));

}else if([length isLessThan:@0]) {
length = (@(-[length doubleValue]));

}
if([prefix boolValue] && ([length isGreaterThanOrEqualTo:(@([[self strlen:value] doubleValue]-[@1 doubleValue]))])) {
NSNumber*  prepend = @([(@([length doubleValue]-[(@([[self strlen:value] doubleValue]-[@1 doubleValue])) doubleValue])) doubleValue]+[@1 doubleValue]);
NSNumber*  counter = @0;
while([counter isLessThan:prepend])
{
value = [@"0" stringByAppendingString:value];
counter = @([counter longLongValue]+1);

}

}else if([length isLessThan:@0] && [originalLengthSet isGreaterThan:@0]) {
NSNumber*  append = @(-[length doubleValue]);
NSNumber*  counter = @0;
while([counter isLessThan:append])
{
value = [value stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}

}
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSString*  result = @"";
NSNumber*  key = @0;
for(NSString*  digit in digits) {
if([key isEqualTo:(length)]) {
result = [@"." stringByAppendingString:result];

}
result = [digit stringByAppendingString:result];
key = @([key longLongValue]+1);

}
if(![[self strpos:result search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"." term:result];
if([[self strlen:split[1]] isEqualTo:@0]) {
result = split[0];

}

}
if([[self strpos:result search:@"."] isEqualTo:(@(-[@1 doubleValue]))] && [[self strlen:result] isGreaterThan:@1] && [[self substr:result  start: @0 length: @1] isEqualTo:@0]) {
result = [self substr:result  start: @1  length:nil];

}
result = [self trim:result];
return result;
}
- (NSString* )placeDecimalAlt: (NSString* ) value length: (NSNumber* ) length removeDecimal: (NSNumber* ) removeDecimal prefix: (NSNumber* ) prefix  {
if(removeDecimal == nil) {
removeDecimal = @false;

}
if(prefix == nil) {
prefix = @false;

}
NSNumber*  originalLengthSet = length;
NSNumber*  unalteredLength = length;
if([unalteredLength isGreaterThanOrEqualTo:[self strlen:value]]) {
NSNumber*  prepend = @([length doubleValue]-[[self strlen:value] doubleValue]);
NSNumber*  counter = @0;
while([counter isLessThanOrEqualTo:prepend])
{
value = [@"0" stringByAppendingString:value];
counter = @([counter longLongValue]+1);

}

}else if([length isLessThan:@0]) {
NSNumber*  append = @(-[length doubleValue]);
NSNumber*  counter = @0;
while([counter isLessThan:append])
{
value = [value stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}

}
length = unalteredLength;
if([length isLessThan:@0]) {
removeDecimal = @true;

}
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSString*  result = @"";
NSNumber*  key = @0;
for(NSString*  digit in digits) {
if([key isEqualTo:(length)]) {
if(![removeDecimal boolValue]) {
result = [@"." stringByAppendingString:result];

}

}
result = [digit stringByAppendingString:result];
key = @([key longLongValue]+1);

}
if([length isEqualTo:[self strlen:result]]) {
result = [@"0." stringByAppendingString:result];

}
if(![[self strpos:result search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"." term:result];
if([[self strlen:split[1]] isEqualTo:@0]) {
result = split[0];

}

}
if([[self strpos:result search:@"."] isEqualTo:(@(-[@1 doubleValue]))] && [[self strlen:result] isGreaterThan:@1] && [[self substr:result  start: @0 length: @1] isEqualTo:@0]) {
result = [self substr:result  start: @1  length:nil];

}
result = [self trim:result];
return result;
}
- (NSString* )padZeros: (NSString* ) value length: (NSNumber* ) length reverse: (NSNumber* ) reverse  {
if(reverse == nil) {
reverse = @false;

}
NSNumber*  counter = @0;
while([counter isLessThan:length])
{
if(![reverse boolValue]) {
value = [value stringByAppendingString:@"0"];

}else {
value = [@"0" stringByAppendingString:value];

}
counter = @([counter longLongValue]+1);

}
return value;
}
- (NSString* )subDivide: (NSString* ) divider value: (NSString* ) value changeBase: (NSNumber* ) changeBase  {
if(changeBase == nil) {
changeBase = @false;

}
NSNumber*  base = @10;
NSNumber*  testSum = @0;
NSNumber*  dividerLength = [self strlen:divider];
divider = [self removeLeadingZeros:divider reverse:@true];
NSNumber*  dividerExponentTranslation = @([dividerLength doubleValue]-[[self strlen:divider] doubleValue]);
dividerLength = [self strlen:divider];
NSMutableArray*  intermediateResults = [[NSMutableArray alloc] initWithArray:@[]];
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  exponentCounter = @0;
NSNumber*  exponent = nil;
for(NSString*  digitItem in digits) {
NSString*  digit = digitItem;
if(![digit isEqualTo:@"0"]) {
NSNumber*  exponentTranslation = @0;
if([[self strlen:digit] isLessThanOrEqualTo:dividerLength]) {
digit = [self padZeros:digit length:dividerLength  reverse:nil];
NSNumber*  digitNum = @([digit intValue]);
NSNumber*  dividerNum = @([divider intValue]);
if([digitNum isLessThan:dividerNum]) {
dividerLength = @([dividerLength doubleValue]+[@1 doubleValue]);

}
exponentTranslation = dividerLength;

}
NSNumber*  exponentAlteration = @0;
exponent = (@([(@([exponentCounter doubleValue]-[exponentTranslation doubleValue])) doubleValue]-[dividerExponentTranslation doubleValue]));
NSNumber*  result = @([digit doubleValue]/[divider doubleValue]);
[intermediateResults addObject:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":[result stringValue], @"exponent":exponent}]];

}
exponentCounter = @([exponentCounter longLongValue]+1);

}
NSString*  result = [self collectResults:intermediateResults  base: base];
return result;
}
- (NSMutableArray* )combinations: (NSMutableArray* ) values  {
NSMutableArray*  combinations = [[NSMutableArray alloc] initWithArray:@[values]];
NSNumber*  key = @0;
for(NSObject*  value in values) {
NSMutableArray*  subValues = [[NSMutableArray alloc] initWithArray:values];
[subValues removeObjectAtIndex:[key longLongValue]];
[combinations addObject:subValues];
if([[self countvalue:subValues] isGreaterThan:@0]) {
NSMutableArray*  subCombinations = [self combinations:subValues];
combinations = [self concat:combinations b:subCombinations];

}
key = @([key longLongValue]+1);

}
NSMutableArray*  result = [[NSMutableArray alloc] initWithArray:@[]];
for(NSMutableArray*  combination in combinations) {
if(![[self inArray:combination arr:result] boolValue] && [[self countvalue:combination] isGreaterThan:@0]) {
[result addObject:combination];

}

}
return result;
}
- (NSMutableDictionary* )logarithmSub: (NSMutableDictionary* ) value base: (NSMutableDictionary* ) base  {
return [self logarithmBase:value base:base];
}
- (void )setLogarithmPrecision: (NSNumber* ) logarithmPrecision  {
[self setLogarithmIterationCount:logarithmPrecision];
}
- (NSMutableDictionary* )logarithm: (NSMutableDictionary* ) value base: (NSMutableDictionary* ) base iterationCount: (NSNumber* ) iterationCount  {
if(base == nil) {
base = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@2, @"remainder":@"0/1"}];

}
if(iterationCount == nil) {
iterationCount = NULL;

}
if(iterationCount == NULL) {
iterationCount = [self logarithmIterationCount];

}
if([base isEqualTo:@"e"]||![[self fractionValues:base[@"remainder"]][0] isEqualTo:@0]||[base[@"value"] isGreaterThan:@10]) {
return [self logarithmSub:value  base: base];

}
NSString*  alteredBase = NULL;
if(![base[@"value"] isEqualTo:@10]) {
alteredBase = [self changeBase:value[@"value"] newBase:base[@"value"] base:nil limitDecimals:nil findLastExponent:nil];

}else {
alteredBase = value[@"value"];

}
NSNumber*  exponent = @([[self strlen:alteredBase] doubleValue]-[@1 doubleValue]);
NSMutableDictionary*  divider = [self executePowerWhole:base power:exponent];
NSMutableDictionary*  division = [self executeDivide:value divider:divider  shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil];
NSMutableArray*  fractionValues = [self fractionValues:division[@"remainder"]];
NSMutableDictionary*  wholePart = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":division[@"value"], @"remainder":@"0/1"}];
NSMutableDictionary*  fractionWhole = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":division[@"value"], @"remainder":@"0/1"}];
NSMutableDictionary*  fractionPart = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":[fractionValues[0] stringByAppendingString:[@"/" stringByAppendingString:([self result:fractionValues[1] termB: fractionWhole[@"value"]])]]}];
NSMutableDictionary*  fractionSet = fractionPart;
NSMutableDictionary*  logarithmCommon = [self logarithmSub:fractionPart base:base];
NSMutableDictionary*  result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":exponent, @"remainder":@"0/1"}];
NSMutableDictionary*  logWholePart = [self logarithmSub:fractionWhole  base: base];
result = [self addTotal:result termB:logarithmCommon  shorten:nil];
result = [self addTotal:result termB:logWholePart  shorten:nil];
return result;
}
- (NSMutableDictionary* )naturalLogarithm: (NSMutableDictionary* ) value  {
NSMutableDictionary*  baseNumerator = [self subtractTotal:value valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] shorten:nil];
NSMutableDictionary*  baseDenominator = [self addTotal:value termB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] shorten:nil];
NSMutableDictionary*  base = [self executeDivide:baseNumerator divider:baseDenominator  shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil];
NSMutableDictionary*  totalSum = base;
NSNumber*  counter = @3;
while([counter isLessThan:[self logarithmIterationCount]])
{
NSMutableDictionary*  addedValue = [self power:base power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":counter, @"remainder":@"0/1"}]];
addedValue = [self executeDivide:addedValue divider:counter  shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil];
totalSum = [self addTotal:totalSum termB:addedValue  shorten:nil];
if([[self truncateFractionsLength] isGreaterThan:@0]) {
totalSum[@"remainder"]=[self executeShortenFraction:totalSum[@"remainder"] bypassTruncation:nil];

}
counter = @([counter doubleValue]+[@2 doubleValue]);

}
totalSum = [self multiplyTotal:totalSum valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@2, @"remainder":@"0/1"}] shorten:nil];
return totalSum;
}
- (NSMutableDictionary* )logarithmBase: (NSMutableDictionary* ) value base: (NSMutableDictionary* ) base  {
NSMutableDictionary*  naturalLogarithmValue = [self naturalLogarithm:value];
if([base isEqualTo:@"e"]) {
return naturalLogarithmValue;

}
NSMutableDictionary*  baseValue = [self naturalLogarithm:base];
NSMutableDictionary*  result = [self executeDivide:naturalLogarithmValue  divider: baseValue  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return result;
}
- (NSString* )quickFraction: (NSString* ) value  {
NSMutableArray*  values = [self fractionValues:value];
NSString*  result = [self realFraction:value decimalPoints:@15  level:nil];
return result;
}
- (NSString* )absoluteFraction: (NSString* ) value  {
if(![[self strpos:value search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"-" term:value];
return split[1];

}
return value;
}
- (NSMutableDictionary* )wholeCommon: (NSString* ) value  {
NSNumber*  ePosition = [self strpos:value search:@"E"];
if(![ePosition isEqualTo:(@(-[@1 doubleValue]))]) {
NSNumber*  eTranslation = [self substr:value  start: @([ePosition doubleValue]+[@1 doubleValue]) length: nil];
value = [self substr:value start:@0 length:ePosition];
NSNumber*  decimalPlace = [self strpos:value search:@"."];
NSNumber*  place = @([decimalPlace doubleValue]+[eTranslation doubleValue]);
place = @([[self strlen:value] doubleValue]-[(@([@1 doubleValue]+[place doubleValue])) doubleValue]);
value = [self placeDecimalAlt:value length:place removeDecimal:@false prefix:@true];

}
NSNumber*  negative = @false;
if(![[self strpos:value search:@"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
negative = @true;

}
NSMutableDictionary*  result = NULL;
value = [self absolute:value];
if(![[self strpos:value search:@"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"." term:value];
NSString*  fraction = [@"0." stringByAppendingString:split[1]];
NSMutableDictionary*  common = [self common:fraction  shorten:nil];
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[self absolute:split[0]], @"remainder":[self absolute:common]}];

}else {
if([value isEqualTo:@""]||value == NULL) {
value = @"0";

}
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}];

}
if([negative boolValue]) {
result = [self negativeValue:result];

}
return result;
}
- (NSString* )wholeNumerator: (NSMutableDictionary* ) value  {
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
NSString*  numerator = [self add:fractionValues[0] termB:[self result:fractionValues[1] termB:value[@"value"]]];
return [numerator stringByAppendingString:[@"/" stringByAppendingString:fractionValues[1]]];
}
- (NSMutableArray* )partialFactorial: (NSString* ) value stop: (NSString* ) stop  {
if([value isEqualTo:@1]||[value isEqualTo:stop]) {
return value;

}
return [self result:value termB:[self partialFactorial:[self subtract:value  termB: @1] stop:stop]];
}
- (NSMutableDictionary* )nextRationalRootSub: (NSString* ) value assignedPower: (NSString* ) assignedPower same: (NSNumber* ) same  {
NSNumber*  root = [[self math] pow:value  b: @([@1 doubleValue]/[assignedPower doubleValue])];
NSString*  rootFloor = [self floor:root];
rootFloor = [self add:rootFloor termB:@1];
value = [self executePowerWhole:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootFloor, @"remainder":@"0/1"}] power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":assignedPower, @"remainder":@"0/1"}]][@"value"];
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"root":rootFloor}];
}
- (NSMutableDictionary* )nextRationalRoot: (NSString* ) value assignedPower: (NSString* ) assignedPower same: (NSNumber* ) same  {
if(same == nil) {
same = @true;

}
NSMutableDictionary*  root = [self root:value n:assignedPower];
if(![root[@"exact"] isEqualTo:@false]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"root":root[@"value"], @"value":value}];

}
NSString*  rootValue = root[@"closestResult"];
rootValue = [self add:rootValue  termB: @1];
return [[NSMutableDictionary alloc] initWithDictionary:@{@"root":rootValue, @"value":[self executePowerWhole:rootValue power:assignedPower][@"value"]}];
}
- (NSMutableArray* )listRationalRoots: (NSString* ) from to: (NSString* ) to assignedPower: (NSString* ) assignedPower  {
if(assignedPower == nil) {
assignedPower = @2;

}
if(![[self larger:from valueB:@1  equal:nil] boolValue]) {
from = @1;

}
NSMutableArray*  rootResults = [[NSMutableArray alloc] initWithArray:@[]];
NSMutableDictionary*  rationalRoot = [self nextRationalRootList:from  assignedPower: assignedPower same: @true previousSetStart: @false];
[rootResults addObject:rationalRoot];
while([[self larger:to valueB:rationalRoot[@"value"] equal:nil] boolValue])
{
rationalRoot = [self nextRationalRootList:[self add:rationalRoot[@"value"] termB: @1] assignedPower: assignedPower same: @true previousSetStart: @true];
if([[self larger:to valueB:rationalRoot[@"value"] equal:nil] boolValue]) {
[rootResults addObject:rationalRoot];

}

}
return rootResults;
}
- (NSMutableDictionary* )nextRationalRootList: (NSString* ) value assignedPower: (NSString* ) assignedPower same: (NSNumber* ) same previousSetStart: (NSNumber* ) previousSetStart  {
if(assignedPower == nil) {
assignedPower = @2;

}
if(same == nil) {
same = @2;

}
if(previousSetStart == nil) {
previousSetStart = @false;

}
NSNumber*  reverse = @false;
NSNumber*  length = @([[self strlen:value] doubleValue]-[@1 doubleValue]);
NSNumber*  power = @2;
NSNumber*  executePower = assignedPower;
NSNumber*  unalteredPower = assignedPower;
assignedPower = @([assignedPower doubleValue]-[@2 doubleValue]);
NSNumber*  largerRoot = @true;
if([largerRoot boolValue]) {
NSString*  decimalMult = NULL;
NSString*  incremented = NULL;
NSString*  decimalMultRoot = NULL;
if([self nextRationalRootStartFirst] != nil) {
NSString*  startRootPrefix = [self subtract:[self nextRationalRoot:value  assignedPower: unalteredPower same: nil][@"root"] termB:@2];
NSString*  startRootPrefixUnaltered = [self result:startRootPrefix termB:startRootPrefix];
NSString*  decimalMultRoot = startRootPrefix;
NSString*  decimalMult = NULL;
NSString*  incrementedRoot = [self add:decimalMultRoot  termB: @1];
NSString*  incremented = [self executePowerWhole:incrementedRoot  power: power][@"value"];
decimalMult = startRootPrefixUnaltered;
NSString*  assignedRoot = decimalMult;
[self setNextRationalRootStartFirst:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":startRootPrefixUnaltered, @"root":startRootPrefix}]];
[self setNextRationalRootStartSecond:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":incremented, @"root":incrementedRoot}]];

}else {
NSMutableDictionary*  first = [self nextRationalRootStartFirst];
NSMutableDictionary*  second = [self nextRationalRootStartSecond];
decimalMultRoot = first[@"root"];
incremented = second[@"value"];
decimalMult = first[@"value"];

}
NSString*  nextRoot = [self add:[self subtract:[self result:incremented  termB: power] termB: decimalMult] termB: power];
NSString*  currentRootRoot = [self add:decimalMultRoot  termB: @2];
NSMutableDictionary*  nextRootValue = NULL;
if([assignedPower isGreaterThanOrEqualTo:@1]) {
nextRootValue = [self executePowerWhole:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":currentRootRoot, @"remainder":@"0/1"}] power:assignedPower];
nextRootValue = [self multiplyTotal:nextRootValue valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":nextRoot, @"remainder":@"0/1"}] shorten:nil][@"value"];

}else {
nextRootValue = nextRoot;

}
NSString*  assignedRoot = nextRootValue;
NSNumber*  count = @0;
NSString*  store = nextRoot;
while(![[self larger:nextRootValue  valueB: value  equal:nil] boolValue])
{
store = nextRoot;
nextRoot = [self add:[self subtract:[self result:nextRoot  termB: power] termB: incremented] termB: power];
currentRootRoot = [self add:currentRootRoot  termB: @1];
if([assignedPower isGreaterThanOrEqualTo:@1]) {
nextRootValue = [self executePowerWhole:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":currentRootRoot, @"remainder":@"0/1"}] power: assignedPower];
nextRootValue = [self multiplyTotal:nextRootValue  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":nextRoot, @"remainder":@"0/1"}] shorten:nil][@"value"];

}else {
nextRootValue = nextRoot;

}
if([[self larger:nextRootValue  valueB: value equal: @false] boolValue]) {
assignedRoot = nextRootValue;

}
incremented = store;

}
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":assignedRoot, @"root":currentRootRoot}];

}else {
NSNumber*  counter = @2;
NSString*  root = [self result:counter  termB: counter];
NSMutableDictionary*  rootDict = [self executePowerWhole:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":counter, @"remainder":@"0/1"}] power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":executePower, @"remainder":@"0/1"}]];
while([[self largerTotal:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}] valueB: rootDict same: @(![same boolValue])] boolValue])
{
counter = @([counter longLongValue]+1);
rootDict = [self executePowerWhole:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":counter, @"remainder":@"0/1"}] power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":executePower, @"remainder":@"0/1"}]];

}
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootDict[@"value"], @"root":counter}];

}
}
- (NSMutableDictionary* )preprocessPower: (NSMutableDictionary* ) value power: (NSMutableDictionary* ) power  {
NSMutableArray*  valueFraction = [self fractionValues:value[@"remainder"]];
if([[self strlen:value[@"value"]] isGreaterThan:@255]) {
NSMutableDictionary*  rationalRootItem = [self nextRationalRoot:value[@"value"] assignedPower: power same: @true];
NSString*  rationalRootSqrt = rationalRootItem[@"root"];
NSString*  rationalRoot = rationalRootItem[@"value"];
NSMutableDictionary*  divisionPart = [self executeDivide:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":rationalRoot, @"remainder":@"0/1"}] divider: value  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rationalRootSqrt, @"remainder":@"0/1"}];
NSMutableDictionary*  partResult = [self executePower:divisionPart  power: power];
result = [self executeDivide:result  divider: partResult shorten: @true   fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return result;

}else {
return [self executePower:value  power: power];

}
}
- (NSMutableDictionary* )intermediateProcessPower: (NSMutableDictionary* ) value power: (NSMutableDictionary* ) power  {
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
NSMutableDictionary*  denominatorRoot = [self nextRationalRoot:fractionValues[1] assignedPower: power  same:nil];
if([denominatorRoot[@"value"] isEqualTo:fractionValues[1]]) {
NSMutableDictionary*  wholeValue = [self makeWhole:value];
NSMutableDictionary*  valueRoot = [self nextRationalRoot:wholeValue[@"value"] assignedPower: power  same:nil];
if([wholeValue[@"value"] isEqualTo:valueRoot[@"value"]]) {
NSMutableDictionary*  division = [self executeDivide:valueRoot[@"root"] divider: denominatorRoot[@"root"] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return division;

}

}
return [self executePower:value  power: power];
}
- (NSMutableDictionary* )power: (NSMutableDictionary* ) value power: (NSMutableDictionary* ) power  {
NSNumber*  negativePower = @false;
if([[self negative:power] boolValue]) {
negativePower = @true;
power = [self absolute:power];

}
NSMutableArray*  powerFractionValues = [self fractionValues:power[@"remainder"]];
NSMutableDictionary*  resultFraction = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}];
if(![powerFractionValues[0] isEqualTo:@0]) {
NSMutableDictionary*  resultFraction = [self preprocessPower:value  power: powerFractionValues[1]];
if(![powerFractionValues[0] isEqualTo:@1]) {
resultFraction = [self executePowerWhole:resultFraction  power: powerFractionValues[0]];

}

}
NSMutableDictionary*  resultWhole = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}];
if(![power[@"value"] isEqualTo:@0]) {
resultWhole = [self executePowerWhole:value  power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":power[@"value"]}]];

}
NSMutableDictionary*  result = [self multiplyTotal:resultWhole  valueB: resultFraction  shorten:nil];
if([negativePower boolValue]) {
result = [self executeDivide:@1  divider: result  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];

}
return result;
}
- (NSMutableDictionary* )executePowerWhole: (NSMutableDictionary* ) value power: (NSMutableDictionary* ) power  {
if(![[self itemIsArray:power] boolValue]) {
power = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":power, @"remainder":@"0/1"}];

}
if(![[self itemIsArray:value] boolValue]) {
value = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}];

}
if([power[@"value"] isEqualTo:@1]) {
return value;

}
if([power[@"value"] isEqualTo:@0]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"1", @"remainder":@"0/1"}];

}
NSNumber*  counter = @0;
NSMutableDictionary*  result = value;
NSString*  powerMax = [self subtract:power[@"value"] termB: @1];
while([[self larger:powerMax  valueB: counter equal: @false] boolValue])
{
result = [self multiplyTotal:result  valueB: value  shorten:nil];
counter = [self add:counter  termB: @1];

}
return result;
}
- (NSMutableDictionary* )makeWhole: (NSMutableDictionary* ) value  {
NSMutableArray*  denominatorFractionValues = [self fractionValues:value[@"remainder"]];
NSMutableDictionary*  multiplier = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":[denominatorFractionValues[1] stringByAppendingString:@"/1"]}];
NSMutableDictionary*  denominatorDivision = [self multiplyTotal:multiplier  valueB: value  shorten:nil];
value = denominatorDivision;
return value;
}
- (NSMutableDictionary* )root: (NSString* ) x n: (NSString* ) n  {
x = [self absolute:x];
if([x isEqualTo:@1]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"exact":@true, @"value":@"1"}];

}else if([x isEqualTo:@0]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"exact":@true, @"value":@"0"}];

}else if([n isEqualTo:@0]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"exact":@true, @"value":@"1"}];

}
NSString*  rootResult = [self rootSub:x  n: n];
NSString*  result = [self executePowerWhole:rootResult  power: n][@"value"];
if([x isEqualTo:result]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"exact":@true, @"value":rootResult}];

}
return [[NSMutableDictionary alloc] initWithDictionary:@{@"exact":@false, @"closestResult":rootResult}];
}
- (NSString* )rootSub: (NSString* ) x n: (NSString* ) n  {
NSString*  guess = @"1";
NSString*  step = @"1";
NSNumber*  counter = @0;
while(@true)
{
NSString*  w = [self executePowerWhole:[self add:guess  termB: step] power: n][@"value"];
if([w isEqualTo:x]) {
return [self add:guess  termB: step];

}else if([[self larger:x  valueB: w  equal:nil] boolValue]) {
step = [self bitShift:step  places: @1  changeBase:nil];

}else if([step isEqualTo:@1]) {
return [self add:guess  termB: @1];

}else {
guess = [self add:guess  termB:[self bitShiftRight:step  places: @0  changeBase:nil]];
step = @1;

}

}
    return @-1;
}
- (NSMutableDictionary* )rootFraction: (NSMutableDictionary* ) mainValue root: (NSString* ) root p: (NSMutableDictionary* ) p  {
if(p == nil) {
p = NULL;

}
if(![[self itemIsArray:mainValue] boolValue]) {
mainValue = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":mainValue, @"remainder":@"0/1"}];

}
if(p == NULL) {
p = [self rootFractionPrecision];

}
NSString*  whole = [self wholeNumerator:mainValue];
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:whole  power: root evaluation: self];
NSMutableDictionary*  num = [rootSolver approximateValue];
NSMutableArray*  x = [[NSMutableArray alloc] initWithArray:@[]];
[x addObject:num];
[x addObject:[self executeDivide:num  divider: root  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil]];
root = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":root, @"remainder":@"0/1"}];
NSNumber*  counter = @0;
while([[self largerTotal:[self absolute:[self subtractTotal:x[1] valueB: x[0] shorten:nil]] valueB: p  same:nil] boolValue])
{
x[0]=x[1];
NSMutableDictionary*  rootTerm = [self subtractTotal:root  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] shorten:nil];
NSMutableDictionary*  firstTerm = [self multiplyTotal:rootTerm  valueB: x[1] shorten:nil];
NSMutableDictionary*  numerator = mainValue;
NSMutableDictionary*  denominator = [self executePowerWhole:x[1] power: rootTerm];
NSMutableDictionary*  secondTerm = [self executeDivide:numerator  divider: denominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  totalTerm = [self addTotal:firstTerm  termB: secondTerm  shorten:nil];
x[1]=[self executeDivide:totalTerm  divider: root  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
x[0]=[self makeIntoStr:x[0]];
x[1]=[self makeIntoStr:x[1]];
[[self math] log:x];
if([[self truncateFractionsLength] isGreaterThan:@0]) {
x[1][@"remainder"]=[self executeShortenFraction:x[1][@"remainder"] bypassTruncation:nil];

}
[[self math] log:x];

}
return x[1];
}
- (NSMutableDictionary* )squareRoot: (NSString* ) value  {
return [self root:value  n: @2];
}
- (NSMutableDictionary* )cubicRoot: (NSString* ) value  {
return [self root:value  n: @3];
}
- (NSString* )trim: (NSString* ) value  {
NSMutableArray*  digits = [self strSplit:value];
NSNumber*  remove = @0;
NSNumber*  decimalPointFound = @false;
NSNumber*  index = @0;
for(NSString*  digit in digits) {
if(![decimalPointFound boolValue]) {
remove = @([remove longLongValue]+1);

}
if([digit isEqualTo:@"."]||![digit isEqualTo:@"0"]) {
decimalPointFound = @true;

}
index = @([index longLongValue]+1);

}
remove = @([remove doubleValue]-[@1 doubleValue]);
value = [self substr:value  start: remove  length:nil];
return value;
}
- (NSMutableArray* )findContinuedFraction: (NSMutableDictionary* ) value power: (NSString* ) power limit: (NSNumber* ) limit precision: (NSNumber* ) precision  {
if(precision == nil) {
precision = NULL;

}
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:NULL  power: power evaluation: self];
NSMutableArray*  result = [rootSolver solveRoot:value  limit: limit precision: precision];
return result;
}
- (NSMutableArray* )squareRootFraction: (NSString* ) value limit: (NSNumber* ) limit  {
if(limit == nil) {
limit = @30;

}
NSMutableDictionary*  sqrt = [self root:value  n: @2];
NSString*  m = NULL;
if(![sqrt[@"exact"] boolValue]) {
m = sqrt[@"closestResult"];

}else {
m = sqrt[@"value"];

}
NSString*  firstM = m;
NSMutableArray*  continuedFraction = [[NSMutableArray alloc] initWithArray:@[m]];
NSString*  xDenominatorValue = [self result:m  termB: m];
NSString*  xDenominator = [self subtract:value  termB: xDenominatorValue];
NSString*  xNumerator = [self add:m  termB: m];
NSMutableDictionary*  xDivision = [self executeDivide:xNumerator  divider: xDenominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
[continuedFraction addObject:xDivision[@"value"]];
NSNumber*  counter = @0;
NSString*  lastXDenominator = xDenominator;
while([counter isLessThan:limit])
{
xDenominatorValue = [self subtract:firstM  termB:[self fractionValues:xDivision[@"remainder"]][0]];
NSString*  xNumeratorValue = xDenominator;
NSString*  xDenominatorSubtraction = [self result:xDenominatorValue  termB: xDenominatorValue];
xDenominator = [self subtract:value  termB: xDenominatorSubtraction];
NSString*  fractionValue = [xNumeratorValue stringByAppendingString:[@"/" stringByAppendingString:xDenominator]];
fractionValue = [self executeShortenFraction:fractionValue  bypassTruncation: @true];
NSMutableArray*  fractionValues = [self fractionValues:fractionValue];
NSString*  xNumeratorMultiplier = fractionValues[0];
xDenominator = fractionValues[1];
xNumerator = [self result:xNumeratorMultiplier  termB: xDenominatorValue];
m = xNumerator;
xDenominatorValue = [self result:m  termB: m];
xNumerator = [self add:firstM  termB: m];
xDivision = [self executeDivide:xNumerator  divider: xDenominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
[continuedFraction addObject:xDivision[@"value"]];
NSMutableArray*  periodic = [self detectPeriodContinuedFraction:continuedFraction];
if(![periodic isEqualTo:@false]) {
return periodic;

}
lastXDenominator = xDenominator;
counter = @([counter longLongValue]+1);

}
return continuedFraction;
}
- (NSMutableArray* )detectPeriodContinuedFraction: (NSMutableArray* ) continuedFraction  {
NSString*  startValue = [self arrayShift:continuedFraction];
NSString*  stopValue = [self result:startValue  termB: @2];
NSMutableArray*  periodValues = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  stop = @false;
for(NSString*  value in continuedFraction) {
if(![stop boolValue]) {
if([value isEqualTo:stopValue]) {
stop = @true;

}else {
[periodValues addObject:value];

}

}

}
if(![stop boolValue]) {
return @false;

}
NSMutableArray*  firstPart = [[NSMutableArray alloc] initWithArray:@[]];
NSMutableArray*  secondPart = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  valuesCount = [[self math] floor:@([[self countvalue:periodValues] doubleValue]/[@2 doubleValue])];
NSNumber*  stopValueFound = @false;
NSNumber*  index = @0;
for(NSString*  value in continuedFraction) {
if([value isEqualTo:stopValue]) {
stopValueFound = @true;

}else {
if(![stopValueFound boolValue]) {
[firstPart addObject:value];

}else {
[secondPart addObject:value];

}

}
index = @([index longLongValue]+1);

}
if([firstPart isEqualTo:secondPart]) {
[self arrayUnshift:firstPart  item: startValue];
[firstPart addObject:stopValue];
return firstPart;

}
return @false;
}
- (void )setPeriodicContinuedFractionPrecision: (NSNumber* ) precision  {
[self setAssignedContinuedFractionResolutionLevelSetting:precision];
}
- (NSMutableDictionary* )resolveContinuedFraction: (NSMutableArray* ) continuedFraction value: (NSString* ) value  {
[self setContinuedFractionResolutionLevel:@0];
if(value != nil) {
[self setAssignedContinuedFractionResolutionLevel:[self assignedContinuedFractionResolutionLevelSetting]];

}else {
[self setAssignedContinuedFractionResolutionLevel:@1];

}
[self setCurrentContinuedFractionWhole:continuedFraction];
NSMutableDictionary*  firstValue = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[self arrayShift:continuedFraction], @"remainder":@"0/1"}];
[self setCurrentContinuedFraction:continuedFraction];
[self setCurrentContinuedFractionSquaredValue:value];
return [self addTotal:firstValue  termB:[self executeDivide:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] divider:[self resolveContinuedFractionSub:continuedFraction] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil] shorten:nil];
}
- (NSMutableDictionary* )resolveContinuedFractionSub: (NSMutableArray* ) continuedFraction  {
NSMutableDictionary*  firstValue = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[self arrayShift:continuedFraction], @"remainder":@"0/1"}];
NSMutableDictionary*  result = NULL;
if([[self countvalue:continuedFraction] isEqualTo:@0]) {
NSMutableArray*  continuedFraction = [self currentContinuedFraction];
[self setContinuedFractionResolutionLevel:@([[self continuedFractionResolutionLevel] doubleValue]+[@1 doubleValue])];
if([self currentContinuedFractionSquaredValue] == NULL) {
return firstValue;

}else if([[self continuedFractionResolutionLevel] isEqualTo:[self assignedContinuedFractionResolutionLevel]]) {
result = [self addTotal:firstValue  termB:[self executeDivide:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] divider:[self terminatingContinuedFraction:[self currentContinuedFractionWhole] value:[self currentContinuedFractionSquaredValue]] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil] shorten:nil];
return result;

}

}
result = [self addTotal:firstValue  termB:[self executeDivide:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@1, @"remainder":@"0/1"}] divider:[self resolveContinuedFractionSub:continuedFraction] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil] shorten:nil];
return result;
}
- (NSMutableArray* )terminatingContinuedFractionValues: (NSMutableArray* ) continuedFraction variable: (NSNumber* ) variable  {
if(variable == nil) {
variable = @false;

}
if([variable boolValue]) {
[self arrayPop:continuedFraction];
[continuedFraction addObject:@1];

}
NSMutableArray*  values = continuedFraction;
values = [self reverse:values];
NSString*  value = [self arrayShift:values];
NSString*  intermediateResult = [@"1/" stringByAppendingString:value];
while([[self countvalue:values] isGreaterThan:@0])
{
NSString*  nextValue = [self arrayShift:values];
NSString*  numerator = [self result:value  termB: nextValue];
NSString*  fractionAddition = [numerator stringByAppendingString:[@"/" stringByAppendingString:value]];
NSString*  intermediateResult = [self addFraction:intermediateResult  valueB: fractionAddition];
intermediateResult = [self flipFraction:intermediateResult];
value = nextValue;

}
return intermediateResult;
}
- (NSMutableArray* )terminatingContinuedFraction: (NSMutableArray* ) continuedFraction value: (NSMutableDictionary* ) value  {
NSString*  variableValuesValue = [self terminatingContinuedFractionValues:continuedFraction  variable: @true];
NSString*  constantValuesValue = [self terminatingContinuedFractionValues:continuedFraction  variable:nil];
NSMutableArray*  variableValues = [self fractionValues:variableValuesValue];
NSMutableArray*  constantValues = [self fractionValues:constantValuesValue];
NSMutableDictionary*  a = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":variableValues[0], @"remainder":@"0/1"}];
NSMutableDictionary*  c = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":variableValues[1], @"remainder":@"0/1"}];
NSMutableDictionary*  b = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":constantValues[0], @"remainder":@"0/1"}];
NSMutableDictionary*  d = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":constantValues[1], @"remainder":@"0/1"}];
NSMutableDictionary*  yApproximate = value;
NSMutableDictionary*  numerator = [self multiplyTotal:d  valueB: yApproximate  shorten:nil];
numerator = [self subtractTotal:b  valueB: numerator  shorten:nil];
NSMutableDictionary*  denominator = [self multiplyTotal:c  valueB: yApproximate  shorten:nil];
denominator = [self subtractTotal:denominator  valueB: a  shorten:nil];
NSMutableDictionary*  result = [self executeDivide:numerator  divider: denominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return result;
}
- (NSString* )flipFraction: (NSString* ) value  {
NSMutableArray*  fractionValues = [self fractionValues:value];
return [fractionValues[1] stringByAppendingString:[@"/" stringByAppendingString:fractionValues[0]]];
}
- (NSMutableDictionary* )executePowerAlterA: (NSString* ) value  {
NSMutableArray*  fractionValues = [self fractionValues:value];
NSString*  k = fractionValues[0];
NSString*  m = fractionValues[1];
NSMutableDictionary*  kmRoot = [self root:[self result:k  termB: m] n: @2];
if(![kmRoot[@"exact"] isEqualTo:@false]) {
return [self executeDivide:k  divider: kmRoot[@"value"] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];

}
return @false;
}
- (NSMutableDictionary* )factorRoot: (NSMutableDictionary* ) value power: (NSString* ) power  {
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:[self wholeNumerator:value] power: power evaluation: self];
NSMutableDictionary*  result = [rootSolver factorRoot];
return result;
}
- (NSMutableDictionary* )solveRemainderSquare: (NSMutableDictionary* ) value remainderSquared: (NSMutableDictionary* ) remainderSquared  {
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:NULL  power: NULL evaluation: self];
NSMutableDictionary*  result = [rootSolver solveRSquare:value  rSquared: remainderSquared];
return result;
}
- (NSMutableDictionary* )reuseSquareRoot: (NSMutableDictionary* ) value knownRoot: (NSMutableDictionary* ) knownRoot  {
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:[self wholeNumerator:value] power: @2 evaluation: self];
NSMutableDictionary*  result = [rootSolver solve:knownRoot];
return result;
}
- (NSMutableDictionary* )rootByDenominator: (NSMutableDictionary* ) value denominatorRoot: (NSString* ) denominatorRoot power: (NSString* ) power  {
RootSolver* rootSolver = [[RootSolver alloc] init];
[rootSolver initialize:[self wholeNumerator:value] power: power evaluation: self];
NSMutableDictionary*  result = [rootSolver rootByDenominatorValue:denominatorRoot];
return result;
}
- (NSMutableDictionary* )executePower: (NSMutableDictionary* ) value power: (NSString* ) power  {
NSString*  quickFraction = [self quickNumeric:value  decimalPlaces:nil];
NSMutableDictionary*  approximateValue = [[self math] pow:quickFraction  b: @([@1 doubleValue]/[power doubleValue])];
approximateValue = [self wholeCommon:approximateValue];
return approximateValue;
}
- (NSString* )resultMultiple: (NSMutableArray* ) values  {
NSString*  result = @"1";
for(NSString*  value in values) {
result = [self result:result  termB: value];

}
return result;
}
- (NSMutableArray* )quadratic: (NSString* ) a b: (NSString* ) b c: (NSString* ) c  {
NSString*  underRoot = [self result:b  termB: b];
NSString*  subtraction = [self resultMultiple:[[NSMutableArray alloc] initWithArray:@[@4, a, c]]];
underRoot = [self subtract:underRoot  termB: subtraction];
NSMutableDictionary*  square = [self executePower:underRoot  power: @2];
if(![[self fractionValues:square[@"remainder"]][0] isEqualTo:@0]) {
return NULL;

}
NSString*  value = [self negativeValue:b];
NSString*  firstValue = [self add:value  termB: square];
NSString*  secondValue = [self subtract:value  termB: square];
NSString*  divider = [self result:@2  termB: a];
NSMutableDictionary*  firstValueDivision = [self executeDivide:firstValue  divider: divider  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  secondValueDivision = [self executeDivide:secondValue  divider: divider  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return [[NSMutableArray alloc] initWithArray:@[firstValueDivision, secondValueDivision]];
}
- (NSMutableDictionary* )smallDivide: (NSString* ) value divider: (NSString* ) divider  {
NSNumber*  mod = [[self math] mod:value  b: divider];
NSNumber*  whole = @([(@([value doubleValue]-[mod doubleValue])) doubleValue]/[divider doubleValue]);
NSString*  modValue = [mod stringValue];
NSString*  wholeValue = [whole stringValue];
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[wholeValue stringByAppendingString:@""], @"remainder":[modValue stringByAppendingString:[@"/" stringByAppendingString:divider]]}];
}
- (NSMutableDictionary* )executeDivide: (NSObject* ) value divider: (NSObject* ) divider shorten: (NSNumber* ) shorten fast: (NSNumber* ) fast numeric: (NSNumber* ) numeric preShorten: (NSNumber* ) preShorten absolute: (NSNumber* ) absolute  {
return [self cleanRemainder:[self makeIntoStr:[self executeDivideMain:value  divider: divider shorten: shorten fast: fast numeric: numeric preShorten: preShorten absolute: absolute]]];
}
- (NSMutableDictionary* )executeDivideMain: (NSObject* ) value divider: (NSObject* ) divider shorten: (NSNumber* ) shorten fast: (NSNumber* ) fast numeric: (NSNumber* ) numeric preShorten: (NSNumber* ) preShorten absolute: (NSNumber* ) absolute  {
if(shorten == nil) {
shorten = @false;

}
if(fast == nil) {
fast = @false;

}
if(numeric == nil) {
numeric = @false;

}
if(preShorten == nil) {
preShorten = @false;

}
if(absolute == nil) {
absolute = @false;

}
NSNumber*  valueNum = value;
NSNumber*  dividerNum = divider;
NSNumber*  isnumValue = @([valueNum isKindOfClass:[NSString class]]);
NSNumber*  isnumDivider = @([dividerNum isKindOfClass:[NSString class]]);
if(![isnumValue boolValue] && ![[self itemIsArray:value] boolValue]) {
value = [valueNum stringValue];

}
if(![isnumDivider boolValue] && ![[self itemIsArray:divider] boolValue]) {
divider = [dividerNum stringValue];

}
if(![[self itemIsArray:value] boolValue] && ![[self itemIsArray:divider] boolValue] && [[self strlen:value] isLessThan:@10] && [[self strlen:divider] isLessThan:@10]) {
return [self smallDivide:value  divider: divider];

}
NSMutableDictionary*  valueDict = value;
NSMutableDictionary*  dividerDict = divider;
NSString*  valueStr = value;
NSString*  dividerStr = divider;
NSNumber*  negative = @false;
if([[self itemIsArray:divider] boolValue] && ![[self itemIsArray:value] boolValue]) {
value = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}];
value = [self makeIntoStr:value];

}
if([[self equalsZero:value] boolValue]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"0/1"}];

}
if([[self equalsZero:divider] boolValue]) {
return NULL;

}
if((([[self negative:value] boolValue] && ![[self negative:divider] boolValue])||(![[self negative:value] boolValue] && [[self negative:divider] boolValue])) && ![absolute boolValue]) {
negative = @true;

}
valueDict = value;
if([value isEqualTo:divider]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"1", @"remainder":@"0/1"}];

}
value = [self absolute:value];
divider = [self absolute:divider];
NSMutableDictionary*  result = NULL;
if(![[self itemIsArray:value] boolValue] && ![[self itemIsArray:divider] boolValue]) {
if([[self larger:divider  valueB: value equal: @false] boolValue]) {
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":[valueStr stringByAppendingString:[@"/" stringByAppendingString:dividerStr]]}];
if([negative boolValue]) {
return [self negativeValue:result];

}
return result;

}
if([[self strlen:value] isEqualTo:[self strlen:divider]]) {
if([[self larger:divider  valueB: value equal: @false] boolValue]) {
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":[valueStr stringByAppendingString:[@"/" stringByAppendingString:dividerStr]]}];
if([negative boolValue]) {
return [self negativeValue:result];

}
return result;

}else {
NSNumber*  counter = (@(-[@1 doubleValue]));
NSString*  lastSubtraction = nil;
NSNumber*  subtraction = value;
while([[self larger:subtraction  valueB: @"0"  equal:nil] boolValue])
{
lastSubtraction = subtraction;
subtraction = [self subtract:subtraction  termB: divider];
counter = @([counter longLongValue]+1);

}
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[counter stringValue], @"remainder":[lastSubtraction stringByAppendingString:[@"/" stringByAppendingString:divider]]}];
if([negative boolValue]) {
return [self negativeValue:result];

}
return result;

}

}

}
if([[self itemIsArray:value] boolValue] && [[self itemIsArray:divider] boolValue] && [valueDict[@"value"] isEqualTo:@0]) {
NSString*  dividerFraction = [self wholeNumerator:divider];
dividerFraction = [self flipFraction:dividerFraction];
valueDict = value;
NSString*  mult = [self multiplyFraction:valueDict[@"remainder"] valueB: dividerFraction  shorten:nil];
NSMutableArray*  fractionValues = [self fractionValues:mult];
fractionValues[0]=[self absolute:fractionValues[0]];
result = [self executeDivide:fractionValues[0] divider: fractionValues[1] shorten: @false fast: @false    numeric:nil  preShorten:nil  absolute:nil];
if([negative boolValue]) {
return [self negativeValue:result];

}
return result;

}
if([[self itemIsArray:divider] boolValue] && [dividerDict[@"value"] isEqualTo:@0]) {
NSString*  valueFraction = @"";
if([[self itemIsArray:value] boolValue]) {
valueDict = value;
NSMutableArray*  valueFractionValues = [self fractionValues:valueDict[@"remainder"]];
NSString*  numerator = nil;
NSString*  denominator = nil;
if(![valueFractionValues[0] isEqualTo:@0]) {
numerator = [self add:[self result:valueDict[@"value"] termB: valueFractionValues[1]] termB: valueFractionValues[0]];
denominator = valueFractionValues[1];

}else {
numerator = valueDict[@"value"];
denominator = @"1";

}
valueFraction = [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];

}else {
valueFraction = [valueStr stringByAppendingString:@"/1"];

}
NSMutableDictionary*  fractionDivision = [self divideFraction:valueFraction  valueB: dividerDict[@"remainder"] shorten:nil];
NSMutableArray*  divisionValues = [self fractionValues:fractionDivision];
return [self executeDivide:divisionValues[0] divider: divisionValues[1] shorten: @false fast: @false    numeric:nil  preShorten:nil  absolute:nil];

}
if([[self itemIsArray:divider] boolValue] && ![[self fractionValues:dividerDict[@"remainder"]][0] isEqualTo:@0]) {
NSMutableArray*  fractionValues = [self fractionValues:dividerDict[@"remainder"]];
NSString*  subtractionMultiplier = fractionValues[0];
NSString*  valueMultiplier = fractionValues[1];
NSString*  valueAddition = [self add:subtractionMultiplier  termB:[self result:dividerDict[@"value"] termB: valueMultiplier]];
NSMutableDictionary*  subtractionMultiplierDict = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":subtractionMultiplier, @"remainder":@"0/1"}];
NSMutableDictionary*  numerator = [self multiplyTotal:subtractionMultiplierDict  valueB: value  shorten:nil];
NSMutableDictionary*  subtraction = [self executeDivide:numerator  divider: valueAddition shorten: @false fast: @false    numeric:nil  preShorten:nil  absolute:nil];
if(![[self itemIsArray:value] boolValue]) {
value = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}];

}
value = [self subtractTotal:value  valueB: subtraction  shorten:nil];
divider = dividerDict[@"value"];
dividerDict = divider;

}else if([[self itemIsArray:divider] boolValue] && [[self fractionValues:dividerDict[@"remainder"]][0] isEqualTo:@0]) {
divider = dividerDict[@"value"];
dividerDict = divider;

}
NSString*  fractionSet = nil;
if([[self itemIsArray:value] boolValue]) {
valueDict = value;
fractionSet = valueDict[@"remainder"];
value = valueDict[@"value"];
NSMutableArray*  fractionValues = [self fractionValues:fractionSet];
if(![[self itemIsArray:divider] boolValue]) {
fractionValues[1]=[self result:fractionValues[1] termB: divider];

}
fractionSet = [fractionValues[0] stringByAppendingString:[@"/" stringByAppendingString:fractionValues[1]]];

}
NSMutableArray*  digits = [self strSplit:value];
NSString*  divideValue = @"";
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"0/1"}];
NSNumber*  key = @0;
for(NSString*  digit in digits) {
if(![digit isEqualTo:@"0"]) {
divideValue = [self padZeros:digit  length: (@([[self countvalue:digits] doubleValue]-[(@([key doubleValue]+[@1 doubleValue])) doubleValue]))  reverse:nil];
NSMutableDictionary*  division = [self divide:divideValue  divider: divider];
result = [self addTotal:result  termB: division  shorten:nil];

}
key = @([key longLongValue]+1);

}
result = result[@"value"];
NSString*  multiplication = [self result:result  termB: divider];
NSString*  remainderResult = [self subtract:value  termB: multiplication];
divideValue = remainderResult;
NSString*  remainder = @"0/1";
NSString*  remainderNumeric = @"0";
if(![divideValue isEqualTo:@"0"]||[[self isset:fractionSet] boolValue]) {
remainder = [divideValue stringByAppendingString:[@"/" stringByAppendingString:divider]];
if([[self isset:fractionSet] boolValue]) {
remainder = [self addFraction:remainder  valueB: fractionSet];

}

}
NSMutableArray*  remainderValues = [self fractionValues:remainder];
if([[self larger:remainderValues[0] valueB: remainderValues[1] equal:nil] boolValue]) {
NSMutableDictionary*  subDivision = [self executeDivide:remainderValues[0] divider: remainderValues[1] shorten: @false fast: @false    numeric:nil  preShorten:nil  absolute:nil];
result = [self add:result  termB: subDivision[@"value"]];
remainder = subDivision[@"remainder"];

}
NSMutableArray*  fractionValues = [self fractionValues:remainder];
if([fractionValues[0] isEqualTo:fractionValues[1]]) {
result = [self add:result  termB: @1];
remainder = @"0/1";

}
if([result isEqualTo:@""]) {
result = @"0";

}
if([shorten boolValue]) {
remainder = [self executeShortenFraction:remainder  bypassTruncation:nil];

}else {
remainder = [self minimizeFraction:remainder];

}
NSNumber*  resNum = result;
NSNumber*  resIskind = @([resNum isKindOfClass:[NSNumber class]]);
if([resIskind boolValue]) {
result = [resNum stringValue];

}
result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":result, @"remainder":remainder}];
if([negative boolValue]) {
result = [self negativeValue:result];

}
result = [self cleanRemainder:result];
return result;
}
- (NSMutableDictionary* )executeDivideSub: (NSString* ) value divider: (NSString* ) divider  {
NSMutableDictionary*  result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"0/1"}];
NSMutableArray*  digits = [self strSplit:value];
NSString*  divideValue = nil;
NSNumber*  key = @0;
for(NSString*  digit in digits) {
divideValue = [self padZeros:digit  length: (@([[self countvalue:digits] doubleValue]-[(@([key doubleValue]+[@1 doubleValue])) doubleValue]))  reverse:nil];
if(![divideValue isEqualTo:@0]) {
NSMutableDictionary*  division = [self divide:divideValue  divider: divider];
result = [self addTotal:result  termB: division  shorten:nil];

}
key = @([key longLongValue]+1);

}
result = result[@"value"];
NSString*  multiplication = [self result:result  termB: divider];
NSString*  remainderResult = [self subtract:value  termB: multiplication];
divideValue = remainderResult;
NSString*  remainder = @"0/1";
NSString*  remainderNumeric = @"0";
if(![divideValue isEqualTo:@"0"]) {
remainder = [divideValue stringByAppendingString:[@"/" stringByAppendingString:divider]];

}
NSMutableArray*  remainderValues = [self fractionValues:remainder];
if([[self larger:remainderValues[0] valueB: remainderValues[1] equal:nil] boolValue]) {
NSMutableDictionary*  subDivision = [self executeDivideSub:remainderValues[0] divider: remainderValues[1]];
result = [self add:result  termB: subDivision];

}
return result;
}
- (NSNumber* )divisible: (NSObject* ) value divider: (NSObject* ) divider  {
NSMutableDictionary*  division = [self executeDivide:value  divider: divider  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  numerator = [self fractionValues:division[@"remainder"]][0];
if([numerator isEqualTo:@0]) {
return @true;

}
return @false;
}
- (NSString* )floor: (NSString* ) value  {
NSNumber*  iskind = @([value isKindOfClass:[NSNumber class]]);
if([iskind boolValue]) {
NSNumber*  numValue = value;
value = [numValue stringValue];

}
if([[self negative:value] boolValue]) {
return [self negativeValue:[self ceil:[self absolute:value]]];

}
if([[self itemIsArray:value] boolValue]) {
NSMutableDictionary*  valueDict = value;
return valueDict[@"value"];

}
if(![[self strpos:value  search: @"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"."  term: value];
return split[0];

}
return value;
}
- (NSMutableDictionary* )executeSubDivide: (NSString* ) value divider: (NSString* ) divider  {
NSMutableDictionary*  division = [self subDivide:value  value: divider  changeBase:nil];
return division;
}
- (NSMutableDictionary* )divide: (NSString* ) value divider: (NSString* ) divider  {
if([value isEqualTo:@"0"]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"0/1"}];

}
if([[self larger:divider  valueB: value  equal:nil] boolValue]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":[value stringByAppendingString:[@"/" stringByAppendingString:divider]]}];

}
NSMutableDictionary*  divisionDict = [self executeSubDivide:value  divider: divider];
NSString*  wholeDivision = [self common:divisionDict  shorten:nil];
NSMutableArray*  fractionValues = [self fractionValues:wholeDivision];
NSString*  division = [@"0." stringByAppendingString:fractionValues[0]];
division = @([@1 doubleValue]/[division doubleValue]);
division = [self floor:division];
NSNumber*  decimalPoint = @([(@([[self strlen:fractionValues[0]] doubleValue]-[[self strlen:fractionValues[1]] doubleValue])) doubleValue]+[@1 doubleValue]);
division = [self placeDecimalAlt:division  length: decimalPoint removeDecimal: @true prefix: @true];
NSString*  minDivision = [self floor:division];
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":minDivision, @"remainder":@"0/1"}];
}
- (NSNumber* )isBinaryPower: (NSString* ) value changeBase: (NSNumber* ) changeBase  {
if(changeBase == nil) {
changeBase = @true;

}
NSString*  binaryValue = value;
if([changeBase boolValue]) {
binaryValue = [self changeBase:value  newBase: @2  base:nil  limitDecimals:nil  findLastExponent:nil];

}
NSMutableArray*  binaryValueDigits = [self strSplit:binaryValue];
NSNumber*  oneCount = @0;
NSNumber*  key = @0;
for(NSString*  digit in binaryValueDigits) {
if([digit isEqualTo:@"1"]) {
oneCount = @([oneCount longLongValue]+1);

}
if([oneCount isGreaterThan:@1]) {
return @false;

}
key = @([key longLongValue]+1);

}
return @true;
}
- (NSString* )binaryMultiplication: (NSString* ) value multiplier: (NSString* ) multiplier  {
NSMutableArray*  multiplierDigits = [self getDigits:multiplier  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  additions = [[NSMutableArray alloc] initWithArray:@[]];
NSString*  prefix = @"";
NSNumber*  key = @0;
for(NSString*  digit in multiplierDigits) {
if([digit isEqualTo:@1]) {
[additions addObject:[value stringByAppendingString:prefix]];

}
prefix = [prefix stringByAppendingString:@"0"];
key = @([key longLongValue]+1);

}
NSString*  total = @"0";
for(NSString*  addition in additions) {
total = [self binaryAddition:total  additionInput: addition];

}
return total;
}
- (NSString* )binaryAddition: (NSString* ) valueInput additionInput: (NSString* ) additionInput  {
NSString*  result = @"";
NSMutableArray*  valueDigits = [self getDigits:valueInput  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  additionDigits = [self getDigits:additionInput  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  value = valueDigits;
NSMutableArray*  adder = additionDigits;
if([[self countvalue:additionDigits] isGreaterThan:[self countvalue:valueDigits]]) {
value = additionDigits;
adder = valueDigits;

}
NSString*  carryBit = @"0";
NSNumber*  key = @0;
for(NSString*  digit in value) {
NSString*  valueAdd = @"0";
if([key isLessThan:@([adder count])]) {
if([digit isEqualTo:@"1"] && [adder[[key longLongValue]] isEqualTo:@"0"]) {
if([carryBit isEqualTo:@"0"]) {
valueAdd = @"1";

}else {
carryBit = @"1";
valueAdd = @"0";

}

}else if([digit isEqualTo:@"1"] && [adder[[key longLongValue]] isEqualTo:@"1"]) {
if([carryBit isEqualTo:@"0"]) {
valueAdd = @"0";
carryBit = @"1";

}else {
valueAdd = @"1";
carryBit = @"1";

}

}else if([digit isEqualTo:@"0"] && [adder[[key longLongValue]] isEqualTo:@"1"]) {
if([carryBit isEqualTo:@"1"]) {
valueAdd = @"0";
carryBit = @"1";

}else {
valueAdd = @"1";

}

}else {
if([carryBit isEqualTo:@"1"]) {
valueAdd = @"1";
carryBit = @"0";

}else {
valueAdd = @"0";

}

}

}else {
if([carryBit isEqualTo:@"1"] && [digit isEqualTo:@"1"]) {
valueAdd = @"0";
[carryBit isEqualTo:@"1"];

}else if([digit isEqualTo:@"0"] && [carryBit isEqualTo:@"1"]) {
valueAdd = @"1";
carryBit = @"0";

}else {
valueAdd = digit;

}

}
result = [result stringByAppendingString:valueAdd];
key = @([key longLongValue]+1);

}
if([carryBit isEqualTo:@"1"]) {
result = [result stringByAppendingString:carryBit];

}
return [self strrev:result];
}
- (NSString* )binarySubtraction: (NSString* ) valueInput subtractionInput: (NSString* ) subtractionInput  {
NSMutableArray*  valueDigits = [self getDigits:valueInput  removeDecimalPoint:nil removeNegative:nil];
NSMutableArray*  additionDigits = [self getDigits:subtractionInput  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  negative = @false;
NSMutableArray*  value = valueDigits;
NSMutableArray*  adder = additionDigits;
NSString*  carryBit = @"0";
NSNumber*  key = @0;
for(NSString*  digit in value) {
if([[self issetAlt:adder  key: key] boolValue]) {
if([adder[[key longLongValue]] isEqualTo:@1] && [digit isEqualTo:@1]) {
value[[key longLongValue]]=@"0";

}else if([adder[[key longLongValue]] isEqualTo:@1] && [digit isEqualTo:@0]) {
value[[key longLongValue]]=@"-1";

}

}
key = @([key longLongValue]+1);

}
value = [self invertNegatives:[self reverse:value]];
NSString*  result = [self implode:@""  term: value];
return result;
}
- (NSString* )invertNegatives: (NSMutableArray* ) value  {
NSNumber*  lastOne = (@(-[@1 doubleValue]));
NSNumber*  key = @0;
for(NSString*  digit in value) {
if([digit isEqualTo:@1]) {
lastOne = key;

}
if([digit isEqualTo:@"-1"] && ![lastOne isEqualTo:(@(-[@1 doubleValue]))]) {
value[[lastOne longLongValue]]=@0;
NSNumber*  counter = @([lastOne doubleValue]+[@1 doubleValue]);
while([counter isLessThanOrEqualTo:key])
{
value[[counter longLongValue]]=@1;
lastOne = counter;
counter = @([counter longLongValue]+1);

}

}
key = @([key longLongValue]+1);

}
return value;
}
- (NSMutableArray* )integerFraction: (NSString* ) value  {
NSMutableArray*  fraction = [self fractionValues:value];
if([[self larger:fraction[0] valueB: fraction[1] equal: @false] boolValue]) {
NSMutableDictionary*  division = [self executeDivide:fraction[0] divider: fraction[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  integer = division[@"value"];
NSString*  wholeNumerator = [self result:integer  termB: fraction[1]];
NSString*  remainingNumerator = [self subtract:fraction[0] termB: wholeNumerator];
return [[NSMutableArray alloc] initWithArray:@[integer,[remainingNumerator stringByAppendingString:[@"/" stringByAppendingString:fraction[1]]]]];

}else {
return [[NSMutableArray alloc] initWithArray:@[@"0", value]];

}
}
- (NSString* )numericValue: (NSMutableDictionary* ) exponentPair  {
if([exponentPair[@"exponent"] isEqualTo:@0]) {
return exponentPair[@"value"];

}
NSMutableArray*  exponentPairList = [[NSMutableArray alloc] initWithArray:@[]];
if([[self isset:exponentPair[@"exponent"]] boolValue]) {
[exponentPairList addObject:exponentPair];

}else {
exponentPairList = exponentPair;

}
NSString*  result = nil;
NSNumber*  prefix = nil;
for(NSMutableDictionary*  exponentPair in exponentPairList) {
NSString*  value = exponentPair[@"value"];
NSNumber*  counter = @0;
NSString*  zeros = @"";
NSNumber*  exponentNum = @([exponentPair[@"exponent"] intValue]);
if([[self strpos:value  search: @"."] isEqualTo:(@(-[@1 doubleValue]))] && [exponentNum isGreaterThanOrEqualTo:@0]) {
while([counter isLessThan:exponentNum])
{
zeros = [zeros stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}
value = [value stringByAppendingString:zeros];

}else {
prefix = @false;
if([exponentNum isLessThan:@0]) {
prefix = @true;

}
value = [self placeDecimal:value  length: exponentPair[@"exponent"] removeDecimal: @true prefix: prefix];

}
value = [self cleanFraction:value];
if(![[self isset:result] boolValue]) {
result = value;

}else {
result = [self add:result  termB: value];

}

}
return result;
}
- (NSString* )realFraction: (NSString* ) value decimalPoints: (NSNumber* ) decimalPoints level: (NSNumber* ) level  {
if(decimalPoints == nil) {
decimalPoints = @10;

}
if(level == nil) {
level = @0;

}
NSString*  negative = @"";
if([[self negative:value] boolValue]) {
negative = @"-";

}
value = [self absolute:value];
NSMutableArray*  fractionValues = [self fractionValues:value];
if(![fractionValues[0] isEqualTo:@"0"]) {
NSMutableArray*  whole = [self integerFraction:value];
NSString*  result = [self substr:[self calculateRealFraction:whole[1] decimalPoints: decimalPoints] start: @0 length: decimalPoints];
return [negative stringByAppendingString:[self numericWhole:whole[0] fraction:[self placeDecimalAlt:result  length: decimalPoints  removeDecimal:nil  prefix:nil] decimalPlaces:nil]];

}
return @"0";
}
- (NSString* )quickNumeric: (NSMutableDictionary* ) value decimalPlaces: (NSNumber* ) decimalPlaces  {
if(decimalPlaces == nil) {
decimalPlaces = @10;

}
return [self numericWhole:value[@"value"] fraction: value[@"remainder"] decimalPlaces: decimalPlaces];
}
- (NSString* )numericWhole: (NSString* ) value fraction: (NSString* ) fraction decimalPlaces: (NSNumber* ) decimalPlaces  {
if(decimalPlaces == nil) {
decimalPlaces = @10;

}
NSNumber*  negative = @false;
if(![[self strpos:value  search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
negative = @true;

}
if(![[self strpos:fraction  search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
negative = @true;

}
value = [self absolute:value];
fraction = [self absolute:fraction];
if(![[self strpos:fraction  search: @"/"] isEqualTo:(@(-[@1 doubleValue]))]) {
fraction = [self realFraction:fraction  decimalPoints: decimalPlaces  level:nil];
fraction = [self absolute:fraction];

}
NSString*  result = nil;
if([[self strpos:fraction  search: @"."] isEqualTo:(@(-[@1 doubleValue]))]) {
result = value;

}else {
NSMutableArray*  fractionValue = [self explode:@"."  term: fraction];
NSString*  addition = [self add:[self absolute:value] termB: fractionValue[0]];
result = [addition stringByAppendingString:[@"." stringByAppendingString:fractionValue[1]]];

}
if([negative boolValue]) {
result = [@"-" stringByAppendingString:result];

}
return result;
}
- (NSMutableDictionary* )cleanRemainder: (NSMutableDictionary* ) value  {
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
NSString*  numeratorClean = [self removeLeadingZeros:[self removeMinus:fractionValues[0]] reverse:nil];
if([numeratorClean isEqualTo:@"0"]) {
value[@"remainder"]=@"0/1";

}else {
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
NSString*  numerator = [self removeLeadingZeros:fractionValues[0] reverse:nil];
NSString*  denominator = [self removeLeadingZeros:fractionValues[1] reverse:nil];
value[@"remainder"]=[numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];

}
return value;
}
- (NSString* )cleanFraction: (NSString* ) value  {
NSNumber*  clean = @true;
if(![[self strpos:value  search: @"."] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"."  term: value];
NSMutableArray*  fraction = [self strSplit:split[1]];
for(NSString*  digit in fraction) {
if(![digit isEqualTo:@"0"]) {
clean = @false;

}

}
if([clean boolValue]) {
return split[0];

}

}
return value;
}
- (NSString* )addPlace: (NSString* ) termA termB: (NSString* ) termB place: (NSNumber* ) place base: (NSNumber* ) base limitDecimals: (NSNumber* ) limitDecimals  {
if(base == nil) {
base = @10;

}
if(limitDecimals == nil) {
limitDecimals = @false;

}
NSNumber*  splitPlace = @([[self strlen:termA] doubleValue]-[place doubleValue]);
NSString*  termARemainder = [self substr:termA  start: splitPlace length:[self strlen:termA]];
NSString*  termAAdd = [self substr:termA  start: @0 length: splitPlace];
NSString*  addition = [self add:termAAdd  termB: termB];
NSString*  result = [addition stringByAppendingString:termARemainder];
return result;
}
- (NSMutableDictionary* )synchronizeValues: (NSString* ) termA termB: (NSString* ) termB  {
NSNumber*  fractionLength = @0;
NSMutableArray*  aSplit = [self explode:@"."  term: termA];
NSMutableArray*  bSplit = [self explode:@"."  term: termB];
if([@([aSplit count]) isGreaterThan:@1]) {
fractionLength = [self strlen:aSplit[1]];

}
if([@([bSplit count]) isGreaterThan:@1] && [[self strlen:bSplit[1]] isGreaterThan:fractionLength]) {
fractionLength = [self strlen:bSplit[1]];

}
NSNumber*  diff = fractionLength;
if([@([aSplit count]) isEqualTo:@1]) {
[aSplit addObject:@""];

}
diff = @([fractionLength doubleValue]-[[self strlen:aSplit[1]] doubleValue]);
NSNumber*  counter = @0;
while([counter isLessThan:diff])
{
aSplit[1]=[aSplit[1] stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}
if([@([bSplit count]) isEqualTo:@1]) {
[bSplit addObject:@""];

}
diff = @([fractionLength doubleValue]-[[self strlen:bSplit[1]] doubleValue]);
counter = @0;
while([counter isLessThan:diff])
{
bSplit[1]=[bSplit[1] stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}
termA = [self implode:@"."  term: aSplit];
termB = [self implode:@"."  term: bSplit];
NSMutableDictionary*  result = [[NSMutableDictionary alloc] initWithDictionary:@{@"a":termA, @"b":termB, @"fractionLength":fractionLength}];
return result;
}
- (NSString* )calculateRealFraction: (NSString* ) value decimalPoints: (NSNumber* ) decimalPoints  {
if([decimalPoints isLessThanOrEqualTo:@0]) {
return @"";

}
NSMutableArray*  fractionValues = [self fractionValues:value];
NSMutableDictionary*  division = [self executeDivide:[self padZeros:@"1"  length:[self strlen:fractionValues[1]] reverse:nil] divider: fractionValues[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  numerator = [self multiplyTotal:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":fractionValues[0], @"remainder":@"0/1"}] valueB: division  shorten:nil];
NSMutableDictionary*  denominator = [self multiplyTotal:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":fractionValues[1], @"remainder":@"0/1"}] valueB: division  shorten:nil];
NSString*  result = numerator[@"value"];
result = [self padZeros:result  length: @([[self strlen:fractionValues[1]] doubleValue]-[[self strlen:result] doubleValue]) reverse: @true];
return [result stringByAppendingString:[self calculateRealFraction:numerator[@"remainder"] decimalPoints: @([decimalPoints doubleValue]-[[self strlen:result] doubleValue])]];
}
- (NSString* )divideFraction: (NSString* ) valueA valueB: (NSString* ) valueB shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
NSMutableArray*  fractionA = [self fractionValues:valueA];
NSMutableArray*  fractionB = [self fractionValues:valueB];
NSString*  result = [[self result:fractionA[0] termB: fractionB[1]] stringByAppendingString:[@"/" stringByAppendingString:[self result:fractionA[1] termB: fractionB[0]]]];
if([shorten boolValue]) {
[self executeShortenFraction:shorten  bypassTruncation:nil];

}
return result;
}
- (NSString* )subtractFraction: (NSString* ) valueA valueB: (NSString* ) valueB  {
NSMutableArray*  common = [self commonDenominator:valueA  valueB: valueB];
NSMutableArray*  fractionA = [self fractionValues:common[0]];
NSMutableArray*  fractionB = [self fractionValues:common[1]];
NSString*  numerator = [self subtract:fractionA[0] termB: fractionB[0]];
NSString*  denominator = fractionA[1];
return [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
}
- (NSMutableDictionary* )subtractTotal: (NSMutableDictionary* ) valueA valueB: (NSMutableDictionary* ) valueB shorten: (NSNumber* ) shorten  {
valueA = [self makeIntoStr:valueA];
valueB = [self makeIntoStr:valueB];
if(shorten == nil) {
shorten = @false;

}
NSMutableDictionary*  result = nil;
if([[self negative:valueA] boolValue] && [[self negative:valueB] boolValue]) {
result = [self subtractTotalSub:[self absolute:valueB] valueB:[self absolute:valueA]];

}else if([[self negative:valueA] boolValue] && ![[self negative:valueB] boolValue]) {
result = [self negativeValue:[self addTotalSub:[self absolute:valueB] valueB:[self absolute:valueA] shorten:nil]];

}else if(![[self negative:valueA] boolValue] && [[self negative:valueB] boolValue]) {
result = [self addTotalSub:[self absolute:valueA] valueB:[self absolute:valueB] shorten:nil];

}else {
result = [self subtractTotalSub:valueA  valueB: valueB];

}
if([shorten boolValue]) {
result[@"remainder"]=[self executeShortenFraction:result[@"remainder"] bypassTruncation:nil];

}
result = [self cleanRemainder:result];
return result;
}
- (NSMutableDictionary* )subtractTotalSub: (NSMutableDictionary* ) valueA valueB: (NSMutableDictionary* ) valueB  {
NSMutableArray*  fractionA = [self fractionValues:valueA[@"remainder"]];
NSMutableArray*  fractionB = [self fractionValues:valueB[@"remainder"]];
fractionA[0]=[self add:fractionA[0] termB:[self result:fractionA[1] termB: valueA[@"value"]]];
fractionB[0]=[self add:fractionB[0] termB:[self result:fractionB[1] termB: valueB[@"value"]]];
NSString*  result = [self subtractFraction:[self makeFraction:fractionA] valueB:[self makeFraction:fractionB]];
NSMutableArray*  fraction = [self fractionValues:result];
NSNumber*  negative = @false;
if([[self negative:fraction[0]] boolValue]) {
negative = @true;

}
NSMutableDictionary*  resultDict = [self executeDivide:[self absolute:fraction[0]] divider: fraction[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
if([negative boolValue]) {
return [self negativeValue:resultDict];

}
return resultDict;
}
- (NSString* )makeFraction: (NSMutableArray* ) fraction  {
return [fraction[0] stringByAppendingString:[@"/" stringByAppendingString:fraction[1]]];
}
- (NSString* )addFraction: (NSString* ) valueA valueB: (NSString* ) valueB  {
NSMutableArray*  fractionA = [self fractionValues:valueA];
NSMutableArray*  fractionB = [self fractionValues:valueB];
if([fractionA[0] isEqualTo:@"0"]) {
return valueB;

}
if([fractionB[0] isEqualTo:@"0"]) {
return valueA;

}
if(![fractionA[1] isEqualTo:fractionB[1]]) {
NSMutableArray*  common = [self commonDenominator:valueA  valueB: valueB];
fractionA = [self fractionValues:common[0]];
fractionB = [self fractionValues:common[1]];

}
NSString*  numerator = [self add:fractionA[0] termB: fractionB[0]];
NSString*  denominator = fractionA[1];
return [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
}
- (NSString* )makeFractionNegative: (NSString* ) fractionValue  {
NSMutableArray*  fractionValues = [self fractionValues:fractionValue];
fractionValues[0]=[self negativeValue:fractionValues[0]];
return [fractionValues[0] stringByAppendingString:[@"/" stringByAppendingString:fractionValues[1]]];
}
- (NSMutableDictionary* )addTotal: (NSMutableDictionary* ) termA termB: (NSMutableDictionary* ) termB shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
NSMutableDictionary*  result = nil;
if([[self negative:termA] boolValue] && [[self negative:termB] boolValue]) {
result = [self negativeValue:[self addTotalSub:[self absolute:termA] valueB:[self absolute:termB] shorten:nil]];

}else if([[self negative:termA] boolValue] && ![[self negative:termB] boolValue]) {
result = [self subtractTotal:[self absolute:termB] valueB:[self absolute:termA] shorten:nil];

}else if(![[self negative:termA] boolValue] && [[self negative:termB] boolValue]) {
result = [self subtractTotal:[self absolute:termA] valueB:[self absolute:termB] shorten:nil];

}else {
result = [self addTotalSub:termA  valueB: termB  shorten:nil];

}
NSMutableArray*  remainderValues = [self fractionValues:result[@"remainder"]];
if([remainderValues[0] isEqualTo:remainderValues[1]]) {
result[@"value"]=[self add:result[@"value"] termB: @1];
result[@"remainder"]=@"0/1";

}else if([[self larger:remainderValues[0] valueB: remainderValues[1] equal:nil] boolValue]) {
NSMutableDictionary*  division = [self executeDivide:remainderValues[0] divider: remainderValues[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
result[@"value"]=[self add:result[@"value"] termB: division[@"value"]];
result[@"remainder"]=division[@"remainder"];

}
if([shorten boolValue]) {
result[@"remainder"]=[self executeShortenFraction:result[@"remainder"] bypassTruncation:nil];

}
result = [self cleanRemainder:result];
return result;
}
- (NSMutableDictionary* )addTotalSub: (NSMutableDictionary* ) valueA valueB: (NSMutableDictionary* ) valueB shorten: (NSNumber* ) shorten  {
if(shorten == nil) {
shorten = @false;

}
NSString*  addition = [self add:valueA[@"value"] termB: valueB[@"value"]];
NSNumber*  valueANegative = @false;
NSNumber*  valueBNegative = @false;
NSString*  fraction = [self addFraction:valueA[@"remainder"] valueB: valueB[@"remainder"]];
NSMutableArray*  fractionValues = [self fractionValues:fraction];
NSNumber*  fractionNegative = @false;
NSMutableDictionary*  division = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@0}];
if([[self larger:fractionValues[0] valueB: fractionValues[1] equal:nil] boolValue]) {
NSString*  subtraction = [self subtract:fractionValues[0] termB: fractionValues[1]];
division[@"value"]=@1;
division[@"remainder"]=[subtraction stringByAppendingString:[@"/" stringByAppendingString:fractionValues[1]]];

}
if([division[@"value"] isGreaterThan:@0]) {
addition = [self add:addition  termB: division[@"value"]];
fraction = division[@"remainder"];
if([shorten boolValue]) {
fraction = [self executeShortenFraction:division[@"remainder"] bypassTruncation:nil];

}

}
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":addition, @"remainder":fraction}];
}
- (NSNumber* )equalsZero: (NSObject* ) value  {
if(![[self itemIsArray:value] boolValue]) {
value = [self removeLeadingZeros:value  reverse:nil];
if([value isEqualTo:@"0"]) {
return @true;

}

}else {
NSMutableDictionary*  valueDict = value;
valueDict[@"value"]=[self removeLeadingZeros:valueDict[@"value"] reverse:nil];
NSNumber*  numerator = [self fractionValues:valueDict[@"remainder"]][0];
numerator = [self removeLeadingZeros:numerator  reverse:nil];
if([valueDict[@"value"] isEqualTo:@"0"] && [numerator isEqualTo:@"0"]) {
return @true;

}

}
return @false;
}
- (NSNumber* )negative: (NSObject* ) value  {
NSMutableDictionary*  valueDict = value;
if([[self itemIsArray:value] boolValue]) {
if([[self isset:valueDict[@"negative"]] boolValue] && [valueDict[@"negative"] boolValue]) {
return @true;

}
if(![[self strpos:valueDict[@"value"] search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]||([valueDict[@"value"] isEqualTo:@0] && ![[self strpos:valueDict[@"remainder"] search: @"-"] isEqualTo:(@(-[@1 doubleValue]))])) {
return @true;

}
return @false;

}
if(![[self strpos:value  search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
return @true;

}
return @false;
}
- (NSString* )negativeValue: (NSMutableDictionary* ) value  {
if([[self itemIsArray:value] boolValue] && [[self isset:value[@"value"]] boolValue]) {
NSMutableArray*  fractionValues = [self fractionValues:value[@"remainder"]];
if(![value[@"value"] isEqualTo:@"0"]) {
value[@"value"]=[self negativeValue:value[@"value"]];

}else if(![fractionValues[0] isEqualTo:@"0"]) {
value[@"remainder"]=[[self negativeValue:fractionValues[0]] stringByAppendingString:[@"/" stringByAppendingString:fractionValues[1]]];

}
return value;

}
if([[self strpos:value  search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
return [@"-" stringByAppendingString:value];

}else {
NSMutableArray*  split = [self explode:@"-"  term: value];
return split[1];

}
}
- (NSObject* )absolute: (NSMutableDictionary* ) value  {
if([[self itemIsArray:value] boolValue] && [[self isset:value[@"value"]] boolValue]) {
return [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[self absolute:value[@"value"]], @"remainder":[self absolute:value[@"remainder"]]}];

}
if(![[self strpos:value  search: @"-"] isEqualTo:(@(-[@1 doubleValue]))]) {
NSMutableArray*  split = [self explode:@"-"  term: value];
return split[1];

}
return value;
}
- (NSNumber* )larger: (NSString* ) valueA valueB: (NSString* ) valueB equal: (NSNumber* ) equal  {
if(equal == nil) {
equal = @true;

}
NSNumber*  larger = @true;
valueA = [self removeLeadingZeros:valueA  reverse:nil];
valueB = [self removeLeadingZeros:valueB  reverse:nil];
if([[self negative:valueA] boolValue] && ![[self negative:valueB] boolValue]) {
return @false;

}else if(![[self negative:valueA] boolValue] && [[self negative:valueB] boolValue]) {
return @true;

}else if([[self negative:valueA] boolValue] && [[self negative:valueB] boolValue]) {
return [self larger:[self absolute:valueB] valueB:[self absolute:valueA] equal: equal];

}
if(![equal boolValue]) {
if([valueA isEqualTo:valueB]) {
return @false;

}

}
if([[self strlen:valueA] isLessThan:[self strlen:valueB]]) {
larger = @false;

}else if([[self strlen:valueA] isEqualTo:[self strlen:valueB]]) {
NSMutableArray*  digitsA = [self strSplit:valueA];
NSMutableArray*  digitsB = [self strSplit:valueB];
NSNumber*  counter = @0;
NSNumber*  performBreak = @false;
while([counter isLessThan:[self countvalue:digitsA]] && [larger boolValue] && ![performBreak boolValue])
{
if([digitsA[[counter longLongValue]] isLessThan:digitsB[[counter longLongValue]]]) {
larger = @false;

}else if([digitsA[[counter longLongValue]] isGreaterThan:digitsB[[counter longLongValue]]]) {
performBreak = @true;

}
counter = @([counter longLongValue]+1);

}

}
return larger;
}
- (NSNumber* )largerTotal: (NSMutableDictionary* ) valueA valueB: (NSMutableDictionary* ) valueB same: (NSNumber* ) same  {
if(same == nil) {
same = @true;

}
if([[self larger:valueA[@"value"] valueB: valueB[@"value"] equal: @false] boolValue]) {
return @true;

}else if([valueA[@"value"] isEqualTo:valueB[@"value"]]) {
NSMutableArray*  common = [self commonDenominator:valueA[@"remainder"] valueB: valueB[@"remainder"]];
NSMutableArray*  fractionA = [self fractionValues:common[0]];
NSMutableArray*  fractionB = [self fractionValues:common[1]];
if([[self larger:fractionA[0] valueB: fractionB[0] equal: same] boolValue]) {
return @true;

}

}
return @false;
}
- (NSNumber* )largerFraction: (NSString* ) valueA valueB: (NSString* ) valueB  {
NSMutableArray*  common = [self commonDenominator:valueA  valueB: valueB];
NSMutableArray*  fractionA = [self fractionValues:common[0]];
NSMutableArray*  fractionB = [self fractionValues:common[1]];
if([[self larger:fractionA[0] valueB: fractionB[0] equal:nil] boolValue]) {
return @true;

}
return @false;
}
- (NSNumber* )even: (NSString* ) value  {
if([value isEqualTo:@0]) {
return @true;

}
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
if([@([digits count]) isEqualTo:@0]) {
return @true;

}
NSNumber*  even = @true;
if([[[self math] mod:digits[0] b: @2] isEqualTo:@0]) {
return @true;

}
return @false;
}
- (NSNumber* )multiple: (NSString* ) value multiple: (NSString* ) multiple  {
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSNumber*  isMultiple = @true;
NSNumber*  key = @0;
for(NSString*  digit in digits) {
if(![[[self math] mod:digit  b: multiple] isEqualTo:@0]) {
isMultiple = @false;

}
key = @([key longLongValue]+1);

}
return isMultiple;
}
- (NSMutableArray* )primeFactorsNew: (NSString* ) value  {
return [[NSMutableArray alloc] initWithArray:@[]];
}
- (NSMutableArray* )primeFactorsAlt: (NSString* ) value  {
return [[NSMutableArray alloc] initWithArray:@[]];
}
- (NSMutableArray* )listDivisors: (NSString* ) value  {
NSMutableArray*  factors = [self primeFactorsAlt:value];
NSMutableArray*  divisors = factors;
NSMutableArray*  combinations = [self combinations:factors];
for(NSMutableArray*  combination in combinations) {
NSString*  result = @1;
for(NSString*  combinationValue in combination) {
NSString*  result = [self result:result  termB: combinationValue];

}
if(![[self inArray:result  arr: divisors] boolValue]) {
[divisors addObject:result];

}

}
[divisors addObject:@1];
divisors = [self arrayUnique:divisors];
return divisors;
}
- (NSString* )digitSum: (NSString* ) value subtract: (NSNumber* ) subtract  {
if(subtract == nil) {
subtract = @false;

}
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSString*  sum = @0;
for(NSString*  digit in digits) {
if(![subtract boolValue]) {
sum = [self add:sum  termB: digit];

}else {
sum = [self subtract:sum  termB: digit];

}

}
return [self absolute:sum];
}
- (NSString* )finalDigitSum: (NSString* ) value  {
while([[self strlen:value] isGreaterThan:@1])
{
value = [self digitSum:value  subtract:nil];

}
return value;
}
- (NSString* )bitShift: (NSString* ) value places: (NSNumber* ) places changeBase: (NSNumber* ) changeBase  {
if(changeBase == nil) {
changeBase = @true;

}
NSString*  binaryValue = nil;
if([changeBase boolValue]) {
binaryValue = [self changeBase:value  newBase: @2  base:nil  limitDecimals:nil  findLastExponent:nil];

}else {
binaryValue = value;

}
binaryValue = [self padZeros:binaryValue  length: places  reverse:nil];
NSString*  resultingValue = nil;
if([changeBase boolValue]) {
resultingValue = [self changeBaseDecimal:binaryValue  oldBase: @2];

}else {
resultingValue = binaryValue;

}
return resultingValue;
}
- (NSString* )bitShiftRight: (NSString* ) value places: (NSNumber* ) places changeBase: (NSNumber* ) changeBase  {
if(changeBase == nil) {
changeBase = @true;

}
NSString*  binaryValue = nil;
if([changeBase boolValue]) {
binaryValue = [self changeBase:value  newBase: @2  base:nil  limitDecimals:nil  findLastExponent:nil];

}else {
binaryValue = value;

}
places = [self subtract:[self strlen:binaryValue] termB: places];
places = [self subtract:places  termB: @"1"];
binaryValue = [self substr:binaryValue  start: @0 length: places];
NSString*  resultingValue = nil;
if([changeBase boolValue]) {
resultingValue = [self changeBaseDecimal:binaryValue  oldBase: @2];

}else {
resultingValue = binaryValue;

}
return resultingValue;
}
- (NSString* )changeBaseDecimal: (NSString* ) value oldBase: (NSString* ) oldBase  {
NSString*  newValue = @"0";
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSString*  exponentValue = @1;
NSNumber*  index = @0;
for(NSString*  digit in digits) {
NSString*  valueAddition = [self result:digit  termB: exponentValue];
newValue = [self add:newValue  termB: valueAddition];
exponentValue = [self result:exponentValue  termB: oldBase];
index = @([index longLongValue]+1);

}
return newValue;
}
- (NSMutableDictionary* )changeBaseTotal: (NSMutableDictionary* ) value newBase: (NSString* ) newBase base: (NSString* ) base limitDecimals: (NSNumber* ) limitDecimals  {
if(base == nil) {
base = @10;

}
if(limitDecimals == nil) {
limitDecimals = @false;

}
if([[self itemIsArray:newBase] boolValue]) {
NSMutableDictionary* newBaseDictAlt = newBase;

newBase = newBaseDictAlt[@"value"];

}
value[@"value"]=[self changeBase:value[@"value"] newBase: newBase base: base limitDecimals: limitDecimals    findLastExponent:nil];
value[@"remainder"]=[self fractionBase:value[@"remainder"] newBase: newBase base: base limitDecimals: limitDecimals];
return value;
}
- (NSString* )changeBase: (NSString* ) value newBase: (NSNumber* ) newBase base: (NSNumber* ) base limitDecimals: (NSNumber* ) limitDecimals findLastExponent: (NSNumber* ) findLastExponent  {
if(base == nil) {
base = @10;

}
NSString*  baseStr = [base stringValue];
if(limitDecimals == nil) {
limitDecimals = @true;

}
if(findLastExponent == nil) {
limitDecimals = @true;

}
NSString*  unalteredValue = value;
if([newBase isEqualTo:@10]) {
return [self changeBaseDecimal:value  oldBase: baseStr];

}
if([newBase isGreaterThan:@10]||[newBase isLessThan:@2]) {
return @false;

}
if([value isEqualTo:@0]) {
return @"0";

}
if([value isEqualTo:@1]) {
return @"1";

}
NSString*  newBaseStr = [newBase stringValue];
NSMutableArray*  digits = [self strSplit:value];
NSNumber*  exponentCount = @([[self countvalue:digits] doubleValue]-[@1 doubleValue]);
NSString*  result = @"0";
NSMutableArray*  exponentValues = [[NSMutableArray alloc] initWithArray:@[]];
NSString*  exponentValue = newBaseStr;
[exponentValues addObject:@1];
[exponentValues addObject:exponentValue];
NSNumber*  counter = @0;
while([counter isLessThan:(@([[self countvalue:digits] doubleValue]-[@2 doubleValue]))])
{
exponentValue = [self result:exponentValue  termB: newBaseStr];
[exponentValues addObject:exponentValue];
counter = @([counter longLongValue]+1);

}
exponentValues = [self reverse:exponentValues];
[self setMaximumBaseChangeExponent:exponentValues[0]];
result = @"";
NSNumber*  exponent = @0;
NSString*  updatedValue = value;
while([exponent isLessThanOrEqualTo:exponentCount])
{
NSString*  digit = digits[[exponent longLongValue]];
NSNumber*  exponentLength = @([exponentCount doubleValue]-[exponent doubleValue]);
if([exponent isEqualTo:(@([[self countvalue:digits] doubleValue]-[@1 doubleValue]))]) {
result = [self addSub:result  termB: digit base: newBase limitDecimals: limitDecimals];

}else {
NSString*  digitValue = digit;
counter = @0;
while([counter isLessThan:exponentLength])
{
digitValue = [digitValue stringByAppendingString:@"0"];
counter = @([counter longLongValue]+1);

}
NSString*  divisionValue = [self implode:@""  term: digits];
NSMutableDictionary* division = [ self executeDivide:divisionValue  divider: exponentValues[[exponent longLongValue]] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  newDigit = division[@"value"];
NSString* subtractValue = [ self result:newDigit  termB: exponentValues[[exponent longLongValue]]];
NSString*  remainder = [self subtract:updatedValue  termB: subtractValue];
updatedValue = remainder;
NSNumber*  countDifference = @([(@([exponentCount doubleValue]+[@1 doubleValue])) doubleValue]-[([self strlen:updatedValue]) doubleValue]);
updatedValue = [self padZeros:updatedValue  length: countDifference reverse: @true];
digits = [self strSplit:updatedValue];
NSString*  digitPrefix = @"";
NSNumber*  newDigitNum = @([newDigit intValue]);
if([newDigitNum isGreaterThan:newBase]) {
newDigit = [self changeBase:newDigit  newBase: newBase  base:nil  limitDecimals:nil  findLastExponent:nil];

}
newDigit = [self padZeros:newDigit  length: exponentLength  reverse:nil];
result = [self addSub:result  termB: newDigit base: newBase limitDecimals: limitDecimals];

}
exponent = @([exponent longLongValue]+1);

}
if([findLastExponent boolValue]) {
NSNumber*  exponentDifference = @([[self strlen:result] doubleValue]-[([self strlen:unalteredValue]) doubleValue]);
exponentValue = exponentValues[0];
counter = @0;
if([exponentDifference isGreaterThan:@0]) {
while([counter isLessThan:exponentDifference])
{
exponentValue = [self result:exponentValue  termB: newBaseStr];
[self setMaximumBaseChangeExponent:exponentValue];
counter = @([counter longLongValue]+1);

}

}else {
NSNumber*  index = [self absolute:exponentDifference];
[self setMaximumBaseChangeExponent:exponentValues[[index longLongValue]]];

}

}
return result;
}
- (NSString* )fractionBase: (NSString* ) value newBase: (NSString* ) newBase base: (NSString* ) base limitDecimals: (NSNumber* ) limitDecimals  {
if(base == nil) {
base = @"10";

}
if(limitDecimals == nil) {
limitDecimals = @false;

}
NSMutableArray*  split = [self explode:@"/"  term: value];
NSString*  numerator = [self changeBase:split[0] newBase: newBase base: base limitDecimals: limitDecimals    findLastExponent:nil];
NSString*  denominator = [self changeBase:split[1] newBase: newBase base: base limitDecimals: limitDecimals    findLastExponent:nil];
return [numerator stringByAppendingString:[@"/" stringByAppendingString:denominator]];
}
- (NSNumber* )decimalMult: (NSString* ) value  {
if([[self strlen:value] isLessThanOrEqualTo:@1]) {
return @false;

}
NSMutableArray*  digits = [self strSplit:value];
NSNumber*  counter = @1;
NSNumber*  isDecimalMult = @true;
while([counter isLessThan:[self countvalue:digits]])
{
if(![digits[[counter longLongValue]] isEqualTo:@0]) {
isDecimalMult = @false;

}
counter = @([counter longLongValue]+1);

}
return isDecimalMult;
}
- (NSNumber* )allDigitsSame: (NSString* ) value  {
NSMutableArray*  digits = [self getDigits:value  removeDecimalPoint:nil removeNegative:nil];
NSString*  firstDigit = digits[0];
for(NSString*  digit in digits) {
if(![digit isEqualTo:firstDigit]) {
return @false;

}

}
return @true;
}
- (NSString* )modulus: (NSString* ) value divider: (NSString* ) divider  {
if([value isEqualTo:@0]||[divider isEqualTo:@0]) {
return @0;

}
if([value isEqualTo:divider]) {
return @0;

}
NSMutableDictionary*  division = [self executeDivide:value  divider: divider  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  numerator = [self fractionValues:division[@"remainder"]][0];
return numerator;
}
- (NSString* )ord: (NSString* ) value modulusValue: (NSString* ) modulusValue  {
NSString*  power = @1;
NSString*  valuePower = @1;
while([power isLessThanOrEqualTo:[self strlen:value]])
{
valuePower = [self result:valuePower  termB: value];
NSString*  modulus = [self modulus:valuePower  divider: modulusValue];
if([modulus isEqualTo:@1]) {
return power;

}
power = [self add:power  termB: @1];

}
return @1;
}
- (NSNumber* )perfectPower: (NSString* ) value  {
NSString*  closestValue = NULL;
NSString*  maxRoot = [self ceil:[self naturalLogarithm:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":value, @"remainder":@"0/1"}]]];
NSString*  power = @2;
while([[self larger:maxRoot  valueB: power  equal:nil] boolValue])
{
NSMutableDictionary*  root = [self root:value  n: power];
if(![root[@"exact"] isEqualTo:@false]) {
return @true;

}
closestValue = root[@"closestResult"];
power = [self add:power  termB: @1];

}
return @false;
}
- (NSString* )gcd: (NSString* ) a b: (NSString* ) b  {
if([a isEqualTo:@"0"]) {
return b;

}
return [self gcd:[self modulus:b  divider: a] b: a];
}
- (NSString* )executeShortenFraction: (NSString* ) value bypassTruncation: (NSNumber* ) bypassTruncation  {
if(bypassTruncation == nil) {
bypassTruncation = @false;

}
NSNumber*  negative = @false;
NSString*  valueUnaltered = value;
if([[self negative:value] boolValue]) {
negative = @true;

}
value = [self absolute:value];
NSMutableArray*  fractionValues = [self fractionValues:value];
if([fractionValues[0] isEqualTo:fractionValues[1]]) {
value = @"1/1";
if([negative boolValue]) {
return [self negativeValue:value];

}
return value;

}
if([fractionValues[0] isEqualTo:@"0"]) {
return @"0/1";

}
if([[self truncateFractionsLength] isGreaterThan:@0] && [bypassTruncation isEqualTo:@false]) {
if([[self strlen:fractionValues[1]] isGreaterThan:[self truncateFractionsLength]]) {
NSString*  realFraction = [self realFraction:value  decimalPoints:[self truncateFractionsLength] level:nil];
NSMutableDictionary*  wholeValueValue = [self wholeCommon:realFraction];
NSString*  wholeValue = [self wholeNumerator:wholeValueValue];
if([negative boolValue]) {
wholeValue = [self negativeValue:wholeValue];

}
return wholeValue;

}
return valueUnaltered;

}
NSString*  a = fractionValues[0];
NSString*  b = fractionValues[1];
NSString*  result = [self shortenFractionGcdSub:a  b: b];
if([negative boolValue]) {
result = [self negativeValue:result];

}
return result;
}
- (NSString* )shortenFractionGcdSub: (NSString* ) a b: (NSString* ) b  {
NSString*  gcd = [self gcd:a  b: b];
if([gcd isEqualTo:@"1"]) {
return [a stringByAppendingString:[@"/" stringByAppendingString:b]];

}
NSString*  aDivided = [self executeDivide:a  divider: gcd  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil][@"value"];
NSString*  bDivided = [self executeDivide:b  divider: gcd  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil][@"value"];
return [self shortenFractionGcdSub:aDivided  b: bDivided];
}
- (NSNumber* )coprime: (NSString* ) a b: (NSString* ) b  {
if([[self gcd:a  b: b] isEqualTo:@1]) {
return @true;

}else {
return @false;

}
}
- (NSString* )modexp: (NSString* ) a b: (NSString* ) b n: (NSString* ) n  {
NSString*  c = @1;
while([[self larger:b  valueB: @0 equal: @false] boolValue])
{
if([[self modulus:b  divider: @2] isEqualTo:@1]) {
c = [self result:c  termB: a];
c = [self modulus:c  divider: n];

}
a = [self result:a  termB: a];
a = [self modulus:a  divider: n];
b = [self bitShiftRight:b  places: @0  changeBase:nil];

}
return c;
}
@end
