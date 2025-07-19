//
//  RootSolver.m
//  noobtest
//
//  Created by siggi on 31.7.2024.
//

#import "RootSolver.h"
#import "PHPFunctions.h"

@implementation RootSolver
- (void )initialize: (NSString* ) value power: (NSString* ) power evaluation: (Evaluation*) evaluation  {
[self setValue:value];
[self setPower:power];
[self setEvaluation:evaluation];
[self setPreviousRoots:[[NSMutableArray alloc] initWithArray:@[]]];
[self setContinuedFraction:[[NSMutableArray alloc] initWithArray:@[]]];
}
- (NSMutableDictionary* )solveRSquare: (NSMutableDictionary* ) value rSquared: (NSMutableDictionary* ) rSquared  {
NSMutableDictionary*  vRoot = [[self evaluation] root:value[@"value"] n: @"2"];
NSString*  vItem = NULL;
if([vRoot[@"exact"] boolValue]) {
vItem = vRoot[@"value"];

}else {
vItem = vRoot[@"closestResult"];

}
NSMutableDictionary*  v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":vItem, @"remainder":@"0/1"}];
NSMutableDictionary*  vSquared = [[self evaluation] multiplyTotal:v  valueB: v  shorten:nil];
NSMutableDictionary*  a = [[self evaluation] executeDivide:value  divider: vSquared  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  b = [[self evaluation] subtractTotal:value  valueB: vSquared  shorten:nil];
NSMutableDictionary*  rValue = [[self evaluation] subtractTotal:value  valueB: rSquared  shorten:nil];
rValue = [[self evaluation] executeDivide:rValue  divider:[[self evaluation] multiplyTotal:v  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"2", @"remainder":@"0/1"}] shorten:nil] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
rValue = [[self evaluation] subtractTotal:rValue  valueB:[[self evaluation] executeDivide:v  divider: @"2"  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil] shorten:nil];
return rValue;
}
- (NSMutableArray* )solveRoot: (NSMutableDictionary* ) value limit: (NSNumber* ) limit precision: (NSMutableDictionary* ) precision  {
if(limit == nil) {
limit = @30;

}
if(precision == nil||precision == NULL) {
precision = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"1/100"}];

}
NSString*  v = value[@"value"];
[[[self evaluation] math] log:[[NSMutableArray alloc] initWithArray:@[@"value is", v]]];
NSMutableDictionary*  vRoot = [[self evaluation] root:v  n:[self power]];
if(![vRoot[@"exact"] boolValue]) {
v = vRoot[@"closestResult"];

}else {
v = vRoot[@"value"];

}
[[[self evaluation] math] log:[[NSMutableArray alloc] initWithArray:@[@"root value is", v]]];
[[self continuedFraction] addObject:v];
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":v, @"remainder":@"0/1"}];
value = [[self evaluation] rootFraction:value  root:[self power] p: precision];
NSNumber*  counter = @0;
while([counter isLessThan:limit])
{
NSMutableDictionary*  remainder = [[self evaluation] subtractTotal:value  valueB: v  shorten:nil];
if([[[self evaluation] equalsZero:remainder] boolValue]) {
return [self continuedFraction];

}
NSMutableDictionary*  remainderInverse = [[self evaluation] executeDivide:@"1"  divider: remainder  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
[[[self evaluation] math] log:[[NSMutableArray alloc] initWithArray:@[@"remainderInverse value is", remainderInverse]]];
[[self continuedFraction] addObject:remainderInverse[@"value"]];
NSMutableArray*  period = [[self evaluation] detectPeriodContinuedFraction:[self continuedFraction]];
if(![period isEqualTo:@false]) {
    //NSLog(@"periodic : %@", period);
return period;

}
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":remainderInverse[@"value"], @"remainder":@"0/1"}];
value = remainderInverse;
counter = @([counter longLongValue]+1);

}
return [self continuedFraction];
}
- (NSMutableDictionary* )factorRoot {
NSMutableArray*  fractionValues = [[self evaluation] fractionValues:[self value]];
NSMutableArray*  numeratorFactors = [[self evaluation] primeFactorsNew:fractionValues[0]];
NSMutableArray*  denominatorFactors = [[self evaluation] primeFactorsNew:fractionValues[1]];
NSMutableArray*  factors = [[NSMutableArray alloc] initWithArray:@[]];
for(NSString*  value in numeratorFactors) {
if(![[[self evaluation] issetAlt:factors  key: value] boolValue]) {
factors[[value longLongValue]]=@"1";

}else {
factors[[value longLongValue]]=[[self evaluation] add:factors[[value longLongValue]] termB: @"1"];

}

}
for(NSString*  value in denominatorFactors) {
if(![[[self evaluation] issetAlt:factors  key: value] boolValue]) {
factors[[value longLongValue]]=@"-1";

}else {
factors[[value longLongValue]]=[[self evaluation] subtract:factors[[value longLongValue]] termB: @"1"];

}

}
NSMutableArray*  resultingFactors = [[NSMutableArray alloc] initWithArray:@[]];
NSNumber*  key = @0;
for(NSString*  value in factors) {
if(![value isEqualTo:@0]) {
[resultingFactors addObject:[[self evaluation] executeDivide:value  divider:[self power] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil]];

}
key = @([key longLongValue]+1);

}
NSMutableDictionary*  result = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"1", @"remainder":@"0/1"}];
key = @0;
for(NSMutableDictionary*  valueItem in resultingFactors) {
NSMutableDictionary*  value = [[self evaluation] power:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":key, @"remainder":@"0/1"}] power: valueItem];
result = [[self evaluation] multiplyTotal:result  valueB: value  shorten:nil];
key = @([key longLongValue]+1);

}
return result;
}
- (NSMutableDictionary* )rootByDenominatorValue: (NSMutableDictionary* ) denominatorRoot  {
NSMutableDictionary*  denominator = denominatorRoot;
NSMutableArray*  fractionValues = [[self evaluation] fractionValues:[self value]];
NSMutableDictionary*  divisionValue = [[self evaluation] executeDivide:fractionValues[0] divider: fractionValues[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  rootResultA = [[self evaluation] root:divisionValue[@"value"] n:[self power]];
NSMutableDictionary*  v = nil;
if([rootResultA[@"exact"] boolValue]) {
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootResultA[@"value"], @"remainder":@"0/1"}];

}else {
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootResultA[@"closestResult"], @"remainder":@"0/1"}];

}
NSMutableDictionary*  vSquared = [[self evaluation] executePowerWhole:v  power:[self power]];
NSMutableDictionary*  remainder = [[self evaluation] subtractTotal:divisionValue  valueB: vSquared  shorten:nil];
NSMutableDictionary*  vd = [[self evaluation] multiplyTotal:v  valueB: denominator  shorten:nil];
NSMutableDictionary*  vdSquared = [[self evaluation] multiplyTotal:vd  valueB: vd  shorten:nil];
NSMutableDictionary*  denominatorSquared = [[self evaluation] executePowerWhole:denominator  power:[self power]];
NSMutableDictionary*  rdSquared = [[self evaluation] multiplyTotal:divisionValue  valueB: denominatorSquared  shorten:nil];
NSMutableDictionary*  rdRoot = [[self evaluation] executePower:rdSquared  power:[self power]];
NSMutableDictionary*  numerator = [[self evaluation] subtractTotal:rdRoot  valueB: vd  shorten:nil];
NSMutableDictionary*  resultDivision = [[self evaluation] executeDivide:numerator  divider: denominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  result = [[self evaluation] addTotal:v  termB: resultDivision  shorten:nil];
return result;
}
- (NSMutableDictionary* )squareRootByDenominator: (NSMutableDictionary* ) denominatorRoot  {
NSMutableDictionary*  denominator = denominatorRoot;
NSMutableArray*  fractionValues = [[self evaluation] fractionValues:[self value]];
NSMutableDictionary*  divisionValue = [[self evaluation] executeDivide:fractionValues[0] divider: fractionValues[1] shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  rootResultA = [[self evaluation] root:divisionValue[@"value"] n: @"2"];
NSMutableDictionary*  v = nil;
if([rootResultA[@"exact"] boolValue]) {
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootResultA[@"value"], @"remainder":@"0/1"}];

}else {
v = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":rootResultA[@"closestResult"], @"remainder":@"0/1"}];

}
NSMutableDictionary*  vSquared = [[self evaluation] multiplyTotal:v  valueB: v  shorten:nil];
NSMutableDictionary*  remainder = [[self evaluation] subtractTotal:divisionValue  valueB: vSquared  shorten:nil];
NSMutableDictionary*  vd = [[self evaluation] multiplyTotal:v  valueB: denominator  shorten:nil];
NSMutableDictionary*  vdSquared = [[self evaluation] multiplyTotal:vd  valueB: vd  shorten:nil];
NSMutableDictionary*  denominatorSquared = [[self evaluation] multiplyTotal:denominator  valueB: denominator  shorten:nil];
NSMutableDictionary*  rdSquared = [[self evaluation] multiplyTotal:remainder  valueB: denominatorSquared  shorten:nil];
rdSquared = [[self evaluation] addTotal:rdSquared  termB: vdSquared  shorten:nil];
NSMutableDictionary*  rdRoot = [[self evaluation] executePower:rdSquared  power: @"2"];
NSMutableDictionary*  numerator = [[self evaluation] subtractTotal:rdRoot  valueB: vd  shorten:nil];
NSMutableDictionary*  resultDivision = [[self evaluation] executeDivide:numerator  divider: denominator  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  result = [[self evaluation] addTotal:v  termB: resultDivision  shorten:nil];
return result;
}
- (NSMutableDictionary* )solve: (NSMutableDictionary* ) knownRoot  {
NSMutableDictionary*  rb = knownRoot;
NSMutableArray*  fractionValues = [[self evaluation] fractionValues:[self value]];
NSString*  k = fractionValues[0];
NSString*  m = fractionValues[1];
NSString*  kUnaltered = k;
NSString*  mUnaltered = m;
k = [[self evaluation] result:k  termB: m];
m = [[self evaluation] result:m  termB: m];
NSMutableDictionary*  km = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":[[self evaluation] result:k  termB: m], @"remainder":@"0/1"}];
NSMutableDictionary*  rbSquared = [[self evaluation] executePowerWhole:rb  power: @"2"];
NSMutableDictionary*  z = [[self evaluation] multiplyTotal:rbSquared  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":k, @"remainder":@"0/1"}] shorten:nil];
NSString*  round = [[self evaluation] round:z];
NSMutableDictionary*  subtraction = [[self evaluation] subtractTotal:z  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":round, @"remainder":@"0/1"}] shorten:nil];
if([[[self evaluation] largerTotal:subtraction  valueB:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":@"0", @"remainder":@"1/100"}] same:nil] boolValue]) {
return @false;

}
z = round;
NSMutableDictionary*  zRootValue = [[self evaluation] root:z  n: @"2"];
if(![zRootValue[@"exact"] boolValue]) {
return @false;

}
z = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":zRootValue[@"value"], @"remainder":@"0/1"}];
NSMutableDictionary*  x = [[self evaluation] executeDivide:k  divider: m  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  mDict = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":m, @"remainder":@"0/1"}];
NSMutableDictionary*  mRoot = [[NSMutableDictionary alloc] initWithDictionary:@{@"value":mUnaltered, @"remainder":@"0/1"}];
NSMutableDictionary*  mb = [[self evaluation] multiplyTotal:mDict  valueB: z  shorten:nil];
mb = [[self evaluation] multiplyTotal:mb  valueB: mRoot  shorten:nil];
NSMutableDictionary*  kmb = [[self evaluation] multiplyTotal:km  valueB: rb  shorten:nil];
return [[self evaluation] executeDivide:kmb  divider: mb  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
}
- (NSMutableDictionary* )approximateValue {
NSMutableArray*  fractionValues = [[self evaluation] fractionValues:[self value]];
NSString*  k = fractionValues[0];
NSString*  m = fractionValues[1];
NSNumber*  powerNum = @([[self power] intValue]);
if([powerNum isGreaterThan:@2]) {
NSString*  powerValue = [[self evaluation] subtract:[self power] termB: @"1"];
NSString*  mPowerValue = [[self evaluation] executePowerWhole:m  power: powerValue][@"value"];
NSString*  K = [[self evaluation] result:k  termB: mPowerValue];
NSString*  M = [[self evaluation] result:m  termB: mPowerValue];
NSMutableDictionary*  kRootValue = [[self evaluation] root:K  n:[self power]];
NSString*  kRoot = nil;
if(![kRootValue[@"exact"] boolValue]) {
kRoot = kRootValue[@"closestResult"];

}else {
kRoot = kRootValue[@"value"];

}
return [[self evaluation] executeDivide:kRoot  divider: m  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];

}
NSString*  kUnaltered = k;
NSString*  mUnaltered = m;
k = [[self evaluation] result:k  termB: m];
m = [[self evaluation] result:m  termB: m];
NSMutableDictionary*  x = [[self evaluation] executeDivide:k  divider: m  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  mSquared1 = [[self evaluation] result:m  termB: m];
NSString*  km1 = [[self evaluation] result:k  termB: m];
NSString*  km = [[self evaluation] result:km1  termB: k];
NSString*  kmRoot = [[self evaluation] result:m  termB: kUnaltered];
NSMutableDictionary*  mSquared = [[self evaluation] executeDivide:[[self evaluation] result:m  termB: km] divider: k  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  kSquared = [[self evaluation] result:km  termB: km];
kSquared = [[self evaluation] executeDivide:kSquared  divider: mSquared  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSString*  mB = km;
NSString*  mRootB = kmRoot;
NSMutableDictionary*  kB = kSquared;
NSString*  mB2 = km;
NSString*  mRootB2 = kmRoot;
NSMutableDictionary*  kB2 = kSquared;
NSString*  mBSquared = [[self evaluation] result:mB  termB: mB];
NSMutableDictionary*  rbSquared = [[self evaluation] executeDivide:mBSquared  divider: mSquared  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  raSquared = [[self evaluation] executeDivide:mSquared  divider: mSquared1  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  rbSquaredRationalRoot = [[self evaluation] root:rbSquared[@"value"] n: @"2"];
NSMutableDictionary*  rb = nil;
if([rbSquaredRationalRoot[@"exact"] boolValue]) {
rb = rbSquaredRationalRoot[@"value"];

}else {
rb = rbSquaredRationalRoot[@"closestResult"];

}
NSMutableDictionary*  mA = [[self evaluation] executeDivide:mB  divider: rb  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
NSMutableDictionary*  kDiM = [[self evaluation] executeDivide:[[NSMutableDictionary alloc] initWithDictionary:@{@"value":kmRoot, @"remainder":@"0/1"}] divider: mA  shorten:nil  fast:nil  numeric:nil  preShorten:nil  absolute:nil];
return kDiM;
}
@end
