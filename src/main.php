<?php declare(strict_types=1);
require_once __DIR__ . './../vendor/autoload.php';

// songs
$imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
$somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
$tooManyCooks = ['c', 'g', 'f'];
$iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
$babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
$creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
$paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
$toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
$bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];

$calculator = new SongComplexityProbabilityCalculator();

$calculator->train($imagine, 'easy');
$calculator->train($somewhere_over_the_rainbow, 'easy');
$calculator->train($tooManyCooks, 'easy');
$calculator->train($iWillFollowYouIntoTheDark, 'medium');
$calculator->train($babyOneMoreTime, 'medium');
$calculator->train($creep, 'medium');
$calculator->train($paperBag, 'hard');
$calculator->train($toxic, 'hard');
$calculator->train($bulletproof, 'hard');

$calculator->setLabelProbabilities();
$calculator->setProbabilityOfChordsInLabels();

print_r($calculator->getLabelProbabilities());
print_r($calculator->classify(['d', 'g', 'e', 'dm']));
print_r($calculator->getLabelProbabilities());
print_r($calculator->classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']));