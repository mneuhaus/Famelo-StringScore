Famelo.StringScore
==================

Quicksilver style string scoring based on
https://github.com/joshaven/string_score


Examples
--------

```

use Famelo\StringScore\String;

String::score("hello world", "axl") //=> 0
String::score("hello world", "ow")  //=> 0.35454545454545455

// And then there is fuzziness
String::score("hello world", "hello wor1")  	//=>0  (the "1" in place of the "l" makes a mismatch)
String::score("hello world", "hello wor1",0.5)  //=>0.6081818181818182 (fuzzy)

// Considers string length
String::score('Hello', 'h') //=>0.52
String::score('He', 'h')    //=>0.6249999999999999  (better match becaus string length is closer)

// Same case matches better than wrong case
String::score('Hello', 'h') //=>0.52
String::score('Hello', 'H') //=>0.5800000000000001

// Acronyms are given a little more weight
String::score("Hillsdale Michigan", "HiMi") > "Hillsdale Michigan", "Hills")
String::score("Hillsdale Michigan", "HiMi") < "Hillsdale Michigan", "Hillsd")
```
