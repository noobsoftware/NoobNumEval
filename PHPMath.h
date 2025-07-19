//
//  PHPIncludedObjects.h
//  noobtest
//
//  Created by siggi jokull on 19.1.2023.
//

#import <Foundation/Foundation.h>
#import "PHPScriptObject.h"
@class PHPScriptFunction;
@class PHPReturnResult;
@class PHPScriptVariable;
@class PHPInterpretation;
@class PHPVariableReference;
@class WKWebView;
@class Evaluation;


@interface PHPMath : PHPScriptObject

@property(nonatomic) Evaluation* evaluation;
- (void) init: (PHPScriptFunction*) context;
//- (NSNumber*) mult: (NSNumber*) a b: (NSNumber*) b;
@end
