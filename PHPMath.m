//
//  PHPIncludedObjects.m
//  noobtest
//
//  Created by siggi jokull on 19.1.2023.
//

#import "PHPMath.h"
#import "PHPScriptFunction.h"
#import "PHPReturnResult.h"
#import "PHPScriptVariable.h"
#import "PHPInterpretation.h"
#import "PHPVariableReference.h"
#import <WebKit/WebKit.h>
#import "PHPFunctions.h"

@implementation PHPMath



- (void) init: (PHPScriptFunction*) context {
    [self initArrays];
    
    [self setGlobalObject:true];
    
    [self setEvaluation:[[Evaluation alloc] init]];
    [[self evaluation] initializeItems];
    
    PHPScriptFunction* get_digits = [[PHPScriptFunction alloc] init];
    [get_digits setIsAsync:false];
    [get_digits initArrays];
    [self setDictionaryValue:@"get_digits" value:get_digits];
    [get_digits setPrototype:self];
    [get_digits setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)input;
        
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSNumber* b = nil;
        if(![inputB isEqualTo:@"NULL"]) {
            b = [self makeIntoNumber:inputB];
        }
        
        NSObject* inputC = values[0][2];
        inputC = [self parseInputVariable:inputC];
        NSNumber* c = nil;
        if(![inputC isEqualTo:@"NULL"]) {
            c = [self makeIntoNumber:inputC];
        }
        Evaluation* evaluation = [self evaluation];
        
        return [[self interpretation] makeIntoObjects:[evaluation getDigits:a removeDecimalPoint:b removeNegative:c]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
   
    PHPScriptFunction* set_configuration = [[PHPScriptFunction alloc] init];
    [set_configuration setIsAsync:false];
    [set_configuration initArrays];
    [self setDictionaryValue:@"set_configuration" value:set_configuration];
    [set_configuration setPrototype:self];
    [set_configuration setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        ToJSON* toJSON = [[ToJSON alloc] init];
        input = [toJSON toJSON:[self parseInputVariable:input]];
        
        Evaluation* evaluation = [self evaluation];
        //[evaluation setConfiguration:input];
        return @"NULL";
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* multiply_total = [[PHPScriptFunction alloc] init];
    [multiply_total setIsAsync:false];
    [multiply_total initArrays];
    [self setDictionaryValue:@"multiply_total" value:multiply_total];
    [multiply_total setPrototype:self];
    [multiply_total setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        ////NSLog(@"multiply_total - %@", values);
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        ////NSLog(@"multiply_total - %@", a);
        a[@"value"] = [self makeIntoString:a[@"value"]];
        ////NSLog(@"multiply_total - %@", a);
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        //NSString* b = (NSString*)[self makeIntoString:inputB];
        NSMutableDictionary* b = [toJSON toJSON:inputB];
        ////NSLog(@"multiply_total - %@", b);
        b[@"value"] = [self makeIntoString:b[@"value"]];
        ////NSLog(@"multiply_total - %@", b);
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation multiplyTotal:a valueB:b shorten:nil]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* subtract_total = [[PHPScriptFunction alloc] init];
    [subtract_total setIsAsync:false];
    [subtract_total initArrays];
    [self setDictionaryValue:@"subtract_total" value:subtract_total];
    [subtract_total setPrototype:self];
    [subtract_total setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        a[@"value"] = [self makeIntoString:a[@"value"]];
        
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        //NSString* b = (NSString*)[self makeIntoString:inputB];
        NSMutableDictionary* b = [toJSON toJSON:inputB];
        b[@"value"] = [self makeIntoString:b[@"value"]];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation subtractTotal:a valueB:b shorten:nil]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* root_fraction = [[PHPScriptFunction alloc] init];
    [root_fraction setIsAsync:false];
    [root_fraction initArrays];
    [self setDictionaryValue:@"root_fraction" value:root_fraction];
    [root_fraction setPrototype:self];
    [root_fraction setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        a[@"value"] = [self makeIntoString:a[@"value"]];
        
        NSString* b = values[0][1];
        b = [self parseInputVariable:b];
        b = [self makeIntoString:b];
        
        
        /*NSNumber* c = values[0][2];
        c = [self parseInputVariable:c];
        c = [self makeIntoNumber:c];*/
        
        
        NSMutableDictionary* c = [self parseInputVariable:values[0][2]];
        c = [toJSON toJSON:c];
        c[@"value"] = [self makeIntoString:c[@"value"]];
        
        NSNumber* truncateFractionsLength = values[0][3];
        truncateFractionsLength = [self parseInputVariable:truncateFractionsLength];
        truncateFractionsLength = [self makeIntoNumber:truncateFractionsLength];
        
        Evaluation* evaluation = [self evaluation];
        //[evaluation assignTruncateFractions:truncateFractionsLength];
        [evaluation setTruncateFractionsLength:truncateFractionsLength];
        
        return [[self interpretation] makeIntoObjects:[evaluation rootFraction:a root:b p:c]];
    } name:@"main"];
    
    
    PHPScriptFunction* add_total = [[PHPScriptFunction alloc] init];
    [add_total setIsAsync:false];
    [add_total initArrays];
    [self setDictionaryValue:@"add_total" value:add_total];
    [add_total setPrototype:self];
    [add_total setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        a[@"value"] = [self makeIntoString:a[@"value"]];
        
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        //NSString* b = (NSString*)[self makeIntoString:inputB];
        NSMutableDictionary* b = [toJSON toJSON:inputB];
        b[@"value"] = [self makeIntoString:b[@"value"]];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation addTotal:a termB:b shorten:nil]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* find_continued_fraction = [[PHPScriptFunction alloc] init];
    [find_continued_fraction setIsAsync:false];
    [find_continued_fraction initArrays];
    [self setDictionaryValue:@"find_continued_fraction" value:find_continued_fraction];
    [find_continued_fraction setPrototype:self];
    [find_continued_fraction setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        //NSString* a = (NSString*)[self makeIntoString:input];
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        
        NSObject* inputB = values[0][1];
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        
        NSObject* limit = values[0][2];
        limit = [self parseInputVariable:limit];
        limit = [self makeIntoNumber:limit];
        
        NSObject* precision = values[0][3];
        precision = [self parseInputVariable:precision];
        precision = [toJSON toJSON:precision];
        //precision = [self makeIntoNumber:precision];
        ((NSMutableDictionary*)precision)[@"value"] = [self makeIntoString:((NSMutableDictionary*)precision)[@"value"]];
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation findContinuedFraction:a power:b limit:limit precision:precision]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* assign_continued_fraction = [[PHPScriptFunction alloc] init];
    [assign_continued_fraction setIsAsync:false];
    [assign_continued_fraction initArrays];
    [self setDictionaryValue:@"assign_truncate_fractions" value:assign_continued_fraction];
    [assign_continued_fraction setPrototype:self];
    [assign_continued_fraction setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSNumber* a = (NSNumber*)[self makeIntoNumber:input];
        
        Evaluation* evaluation = [self evaluation];
        [evaluation setTruncateFractionsLength:a];
        return @"NULL";
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* result = [[PHPScriptFunction alloc] init];
    [result setIsAsync:false];
    [result initArrays];
    [self setDictionaryValue:@"result" value:result];
    [result setPrototype:self];
    [result setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation result:a termB:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
   
    PHPScriptFunction* common = [[PHPScriptFunction alloc] init];
    [common setIsAsync:false];
    [common initArrays];
    [self setDictionaryValue:@"common" value:common];
    [common setPrototype:self];
    [common setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        //NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        /*inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];*/
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation common:a shorten:nil]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* small_divide = [[PHPScriptFunction alloc] init];
    [small_divide setIsAsync:false];
    [small_divide initArrays];
    [self setDictionaryValue:@"small_divide" value:small_divide];
    [small_divide setPrototype:self];
    [small_divide setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation smallDivide:a divider:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* root = [[PHPScriptFunction alloc] init];
    [root setIsAsync:false];
    [root initArrays];
    [self setDictionaryValue:@"root" value:root];
    [root setPrototype:self];
    [root setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation root:a n:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* execute_power_whole = [[PHPScriptFunction alloc] init];
    [execute_power_whole setIsAsync:false];
    [execute_power_whole initArrays];
    [self setDictionaryValue:@"execute_power_whole" value:execute_power_whole];
    [execute_power_whole setPrototype:self];
    [execute_power_whole setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        ToJSON* toJSON = [[ToJSON alloc] init];
        NSMutableDictionary* a = [toJSON toJSON:input];
        a[@"value"] = [self makeIntoString:a[@"value"]];
        
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        //NSString* b = (NSString*)[self makeIntoString:inputB];
        NSMutableDictionary* b = [toJSON toJSON:inputB];
        b[@"value"] = [self makeIntoString:b[@"value"]];
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation executePowerWhole:a power:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    PHPScriptFunction* bit_shift_right = [[PHPScriptFunction alloc] init];
    [bit_shift_right setIsAsync:false];
    [bit_shift_right initArrays];
    [self setDictionaryValue:@"bit_shift_right" value:bit_shift_right];
    [bit_shift_right setPrototype:self];
    [bit_shift_right setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* value = [self makeIntoString:input];
        
        NSNumber* new_base = values[0][1];
        new_base = [self parseInputVariable:new_base];
        new_base = [self makeIntoNumber:new_base];
        
        NSNumber* base = nil;
        
        if([values[0] count] > 2) {
            base = values[0][2];
            base = [self parseInputVariable:base];
            base = [self makeIntoNumber:base];
        }
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation bitShiftRight:value places:new_base changeBase:base]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* bit_shift = [[PHPScriptFunction alloc] init];
    [bit_shift setIsAsync:false];
    [bit_shift initArrays];
    [self setDictionaryValue:@"bit_shift" value:bit_shift];
    [bit_shift setPrototype:self];
    [bit_shift setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* value = [self makeIntoString:input];
        
        NSNumber* new_base = values[0][1];
        new_base = [self parseInputVariable:new_base];
        new_base = [self makeIntoNumber:new_base];
        
        NSNumber* base = nil;
        
        if([values[0] count] > 2) {
            base = values[0][2];
            base = [self parseInputVariable:base];
            base = [self makeIntoNumber:base];
        }
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation bitShift:value places:new_base changeBase:base]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* change_base = [[PHPScriptFunction alloc] init];
    [change_base setIsAsync:false];
    [change_base initArrays];
    [self setDictionaryValue:@"change_base" value:change_base];
    [change_base setPrototype:self];
    [change_base setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* value = [self makeIntoString:input];
        
        NSNumber* new_base = values[0][1];
        new_base = [self parseInputVariable:new_base];
        new_base = [self makeIntoNumber:new_base];
        
        NSNumber* base = nil;
        
        if([values[0] count] > 2) {
            base = values[0][2];
            base = [self parseInputVariable:base];
            base = [self makeIntoNumber:base];
        }
        
        NSNumber* limitDecimals = nil;
        if([values[0] count] > 3) {
            limitDecimals = values[0][3];
            limitDecimals = [self parseInputVariable:limitDecimals];
            limitDecimals = [self makeIntoNumber:limitDecimals];
        }
        
        NSNumber* findLastExponent = nil;
        if([values[0] count] > 4) {
            findLastExponent = values[0][4];
            findLastExponent = [self parseInputVariable:findLastExponent];
            findLastExponent = [self makeIntoNumber:findLastExponent];
        }
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation changeBase:value newBase:new_base base:base limitDecimals:limitDecimals findLastExponent:findLastExponent]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* divide_sub = [[PHPScriptFunction alloc] init];
    [divide_sub setIsAsync:false];
    [divide_sub initArrays];
    [self setDictionaryValue:@"divide_sub" value:divide_sub];
    [divide_sub setPrototype:self];
    [divide_sub setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation divide:a divider:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* add = [[PHPScriptFunction alloc] init];
    [add setIsAsync:false];
    [add initArrays];
    [self setDictionaryValue:@"add" value:add];
    [add setPrototype:self];
    [add setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation add:a termB:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* subtract = [[PHPScriptFunction alloc] init];
    [subtract setIsAsync:false];
    [subtract initArrays];
    [self setDictionaryValue:@"subtract" value:subtract];
    [subtract setPrototype:self];
    [subtract setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)[self makeIntoString:input];
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)[self makeIntoString:inputB];
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation subtract:a termB:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* divide = [[PHPScriptFunction alloc] init];
    [divide setIsAsync:false];
    [divide initArrays];
    [self setDictionaryValue:@"divide" value:divide];
    [divide setPrototype:self];
    [divide setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = input;
        if(![a isKindOfClass:[PHPScriptObject class]]) {
            a = [self makeIntoString:a];
        } else {
            ToJSON* toJSON = [[ToJSON alloc] init];
            a = [toJSON toJSON:a];
        }
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = inputB;
        if(![b isKindOfClass:[PHPScriptObject class]]) {
            b = [self makeIntoString:b];
        } else {
            ToJSON* toJSON = [[ToJSON alloc] init];
            b = [toJSON toJSON:b];
        }
        /*Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];*/
        Evaluation* evaluation = [self evaluation];
        return [[self interpretation] makeIntoObjects:[evaluation executeDivide:a divider:b shorten:nil fast:nil numeric:nil preShorten:nil absolute:nil]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* add_fraction = [[PHPScriptFunction alloc] init];
    [add_fraction setIsAsync:false];
    [add_fraction initArrays];
    [self setDictionaryValue:@"add_fraction" value:add_fraction];
    [add_fraction setPrototype:self];
    [add_fraction setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSString* a = (NSString*)input;
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSString* b = (NSString*)inputB;
        Evaluation* evaluation = [[Evaluation alloc] init];
        [evaluation initializeItems];
        return [[self interpretation] makeIntoObjects:[evaluation addFraction:a valueB:b]];
        //return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* mod = [[PHPScriptFunction alloc] init];
    [mod setIsAsync:false];
    [mod initArrays];
    [self setDictionaryValue:@"mod" value:mod];
    [mod setPrototype:self];
    [mod setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSNumber* a = (NSNumber*)input;
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSNumber* b = (NSNumber*)inputB;
        return @([a intValue] % [b intValue]);
    } name:@"main"];
    
    PHPScriptFunction* pow = [[PHPScriptFunction alloc] init];
    [pow setIsAsync:false];
    [pow initArrays];
    [self setDictionaryValue:@"pow" value:pow];
    [pow setPrototype:self];
    [pow setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSNumber* a = (NSNumber*)input;
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSNumber* b = (NSNumber*)inputB;
        return @(powf([a floatValue], [b floatValue]));
    } name:@"main"];
    
    PHPScriptFunction* mult = [[PHPScriptFunction alloc] init];
    [mult setIsAsync:false];
    [mult initArrays];
    [self setDictionaryValue:@"mult" value:mult];
    [mult setPrototype:self];
    [mult setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        ////////NSLog(@"in mult");
        NSObject* input = values[0][0];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSNumber* a = (NSNumber*)input;
        NSObject* inputB = values[0][1];
        //////////////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        NSNumber* b = (NSNumber*)inputB;
        return @([a doubleValue]*[b doubleValue]);
    } name:@"main"];
    
    PHPScriptFunction* round = [[PHPScriptFunction alloc] init];
    [round initArrays];
    [self setDictionaryValue:@"round" value:round];
    [round setPrototype:self];
    [round setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        NSObject* input = values[0][0];
        //////////NSLog(@"input: %@", input);
        input = [self parseInputVariable:input];
        //////////NSLog(@"input: %@", input);
        NSNumber* numberValue = [self makeIntoNumber:input];
        float floatValue = [numberValue floatValue];
        int rounded = roundf(floatValue);
        NSNumber* roundedNumber = @(rounded);
        //////////NSLog(@"roundedNumber: %@", roundedNumber);
        /*PHPReturnResult* result = [[PHPReturnResult alloc] init];
        //PHPScriptVariable* variable = [[PHPScriptVariable alloc] init];
        [result construct:[[self interpretation] makeIntoObjects:@"test"]];
        
        return result;*/
        return NULL;
    } name:@"main"];
    
    
    PHPScriptFunction* equals = [[PHPScriptFunction alloc] init];
    [equals initArrays];
    [self setDictionaryValue:@"equals" value:equals];
    [equals setPrototype:self];
    [equals setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        NSObject* input = values[0][0];
        //////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        NSObject* inputB = values[0][1];
        //////////////NSLog(@"input %@ _ %@", input, values);
        inputB = [self parseInputVariable:inputB];
        if([input isEqual:inputB]) {
            return @true;
        }
        return @false;
    } name:@"main"];
    
    PHPScriptFunction* round_number = [[PHPScriptFunction alloc] init];
    [round_number initArrays];
    [self setDictionaryValue:@"round_number" value:round_number];
    [round_number setPrototype:self];
    [round_number setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        NSObject* input = values[0][0];
        //////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        
        return [[self interpretation] makeIntoObjects:@(roundf([(NSNumber*)[self makeIntoNumber:input] floatValue]))];
    } name:@"main"];
    
    PHPScriptFunction* random_int = [[PHPScriptFunction alloc] init];
    [random_int initArrays];
    [self setDictionaryValue:@"random_int" value:random_int];
    [random_int setPrototype:self];
    [random_int setClosure:^NSObject *(NSMutableArray *values, PHPScriptFunction *self_instance) {
        NSObject* input = values[0][0];
        //////////////NSLog(@"input %@ _ %@", input, values);
        input = [self parseInputVariable:input];
        if([input isKindOfClass:[NSNumber class]]) {
            int r = arc4random_uniform([(NSNumber*)input intValue]);
            
            return @(r);
        }
        return nil;
        //return [[self interpretation] makeIntoObjects:@(roundf([(NSNumber*)[self makeIntoNumber:input] floatValue]))];
    } name:@"main"];
    
    //int r = arc4random_uniform(74);
}


/*- (NSNumber*) mult: (NSNumber*) a b: (NSNumber*) b {
    return @([a doubleValue] * [b doubleValue]);
}*/
@end
