<?php
namespace Famelo\StringScore;

/**
 * Scores a string against another string.
 */
class StringScore {

	/**
	 *
 	 *  String::score('Hello World', 'he');     //=> 0.5931818181818181
 	 *  String::score('Hello World', 'Hello');  //=> 0.7318181818181818
	 */
	static public function score($string, $word, $fuzziness = 0) {

  		// If the string is equal to the word, perfect word.
		if ($string === $word) {
			return 1;
		}

		//if it's not a perfect word and is empty return 0
		if ($word == "") {
			return  0;
		}

		$runningScore = 0;
		$lString = strtolower($string);
		$stringLength = strlen($string);
		$characters = str_split($string);
		$lWord = strtolower($word);
		$wordLength = strlen($word);
		$startAt = 0;
		$fuzzies = 1;
		$charScore = 0;
		$finalScore = 0;

  		// Cache fuzzyFactor for speed increase
  		if ($fuzziness > 0) {
  			$fuzzyFactor = 1 - $fuzziness;
  		}

		// Walk through word and add up scores.
		// Code duplication occurs to prevent checking fuzziness inside for loop
  		if ($fuzziness) {
    		for($i = 0; $i < $wordLength; ++$i) {

      			// Find next first case-insensitive word of a character.
      			$idxOf = strpos($lString, $lWord[$i], $startAt);

      			if ($idxOf === FALSE) {
        			$fuzzies += $fuzzyFactor;
        			continue;
      			} else if ($startAt === $idxOf) {
        			// Consecutive letter & start-of-string Bonus
        			$charScore = 0.7;
      			} else {
        			$charScore = 0.1;

					// Acronym Bonus
					// Weighing Logic: Typing the first character of an acronym is as if you
					// preceded it with two perfect character matches.
					if ($characters[$idxOf - 1] === ' ') {
						$charScore += 0.8;
					}
				}

      			// Same case bonus.
      			if ($characters[$idxOf] === $word[$i]) {
      				$charScore += 0.1;
      			}

      			// Update scores and startAt position for next round of indexOf
      			$runningScore += $charScore;
      			$startAt = $idxOf + 1;
    		}
  		} else {
    		for ($i = 0; $i < $wordLength; ++$i) {

      			$idxOf = strpos($lString, $lWord[$i], $startAt);

      			if ($idxOf === FALSE) {
        			return 0;
      			} else if ($startAt === $idxOf) {
        			$charScore = 0.7;
      			} else {
        			$charScore = 0.1;
        			if ($characters[$idxOf - 1] === ' ') {
        				$charScore += 0.8;
        			}
      			}

      			if ($characters[$idxOf] === $word[$i]) {
      				$charScore += 0.1;
      			}

      			$runningScore += $charScore;
      			$startAt = $idxOf + 1;
    		}
  		}

  		// Reduce penalty for longer strings.
  		$finalScore = 0.5 * ($runningScore / $stringLength  + $runningScore / $wordLength) / $fuzzies;

  		if (($lWord[0] === $lString[0]) && ($finalScore < 0.85)) {
    		$finalScore += 0.15;
  		}

  		return $finalScore;
	}
}

?>