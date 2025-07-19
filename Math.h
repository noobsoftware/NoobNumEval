//
//  Math.h
//  noobtest
//
//  Created by siggi on 26.7.2024.
//

#ifndef Math_h
#define Math_h

#import <Foundation/Foundation.h>

@interface Math : NSObject
- (NSNumber*) mod: (NSNumber*) a b: (NSNumber*) b;
- (NSNumber*) mult: (NSNumber*) a b: (NSNumber*) b;
- (NSNumber*) pow: (NSNumber*) a b: (NSNumber*) b;
- (NSNumber*) floor: (NSNumber*) a;
- (void) log: (NSObject*) a;
@end

#endif /* Math_h */
