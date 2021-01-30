<?php

use PHPUnit\Framework\TestCase;

final class MainTest extends TestCase
{
    public function testOutPut(): void
    {
        global $imagine, $somewhere_over_the_rainbow, $tooManyCooks, $iWillFollowYouIntoTheDark, $babyOneMoreTime, $creep, $army, $paperBag, $toxic, $bulletproof, $song_11, $songs, $labels, $allChords, $labelCounts, $labelProbabilities, $chordCountsInLabels, $probabilityOfChordsInLabels;

        $this->expectOutputString('Array
(
    [easy] => 0.33333333333333
    [medium] => 0.33333333333333
    [hard] => 0.33333333333333
)
Array
(
    [easy] => 2.0230948271605
    [medium] => 1.8557586131687
    [hard] => 1.8557586131687
)
Array
(
    [easy] => 0.33333333333333
    [medium] => 0.33333333333333
    [hard] => 0.33333333333333
)
Array
(
    [easy] => 1.3433333333333
    [medium] => 1.5060259259259
    [hard] => 1.688422399177
)
');

        require('../src/main.php');
    }
}
