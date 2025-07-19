//
//  RootSolver.h
//  noobtest
//
//  Created by siggi on 31.7.2024.
//

#ifndef RootSolver_h
#define RootSolver_h

#import <Foundation/Foundation.h>
@class Evaluation;

@interface RootSolver : NSObject
@property (nonatomic) NSString* value;
@property (nonatomic) NSString* power;
@property (nonatomic) Evaluation*evaluation;
@property (nonatomic) NSMutableArray* continuedFraction;
@property (nonatomic) NSString* originalValue;
@property (nonatomic) NSMutableArray* previousRoots;
- (void )initialize: (NSString* ) value power: (NSString* ) power evaluation: (Evaluation*) evaluation ;
- (NSMutableDictionary* )solveRSquare: (NSMutableDictionary* ) value rSquared: (NSMutableDictionary* ) rSquared ;
- (NSMutableArray* )solveRoot: (NSMutableDictionary* ) value limit: (NSNumber* ) limit precision: (NSMutableDictionary* ) precision ;
- (NSMutableDictionary* )factorRoot;
- (NSMutableDictionary* )rootByDenominatorValue: (NSMutableDictionary* ) denominatorRoot ;
- (NSMutableDictionary* )squareRootByDenominator: (NSMutableDictionary* ) denominatorRoot ;
- (NSMutableDictionary* )solve: (NSMutableDictionary* ) knownRoot ;
- (NSMutableDictionary* )approximateValue;
@end

#endif /* RootSolver_h */
