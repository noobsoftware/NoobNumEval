//
//  Math.m
//  noobtest
//
//  Created by siggi on 26.7.2024.
//

#import "Math.h"


@implementation Math

- (NSNumber*) mod:(NSNumber *)a b:(NSNumber *)b {
    
    return @([a intValue] % [b intValue]);
}

- (NSNumber*) mult:(NSNumber *)a b:(NSNumber *)b {
    
    return @([a intValue] * [b intValue]);
}

- (NSNumber*) pow:(NSNumber *)a b:(NSNumber *)b {
    
    return @(powf([a floatValue], [b floatValue]));
}

- (NSNumber*) floor:(NSNumber *)a {
    
    return @(floor([a doubleValue]));
}

- (void) log: (NSObject*) value {
    //NSLog(@"%@", value);
}

/*- (void) call {
    int pid = [[NSProcessInfo processInfo] processIdentifier];
    NSPipe *pipe = [NSPipe pipe];
    NSFileHandle *file = pipe.fileHandleForReading;

    NSTask *task = [[NSTask alloc] init];
    task.launchPath = @"/usr/bin/grep";
    task.arguments = @[@"foo", @"bar.txt"];
    task.standardOutput = pipe;

    [task launch];

    NSData *data = [file readDataToEndOfFile];
    [file closeFile];

    NSString *grepOutput = [[NSString alloc] initWithData: data encoding: NSUTF8StringEncoding];
    //NSLog (@"grep returned:\n%@", grepOutput);
}*/
@end
