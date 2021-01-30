<?php



class SongComplexityProbabilityCalculator
{
    public function train($chords, $label)
    {
        $GLOBALS['songs'][] = [$label, $chords];
        for ($i = 0; $i < count($chords); $i++) {
            if (!in_array($chords[$i], $GLOBALS['allChords'])) {
                $GLOBALS['allChords'][] = $chords[$i];
            }
        }
        if (!!(in_array($label, array_keys($GLOBALS['labelCounts'])))) {
            $GLOBALS['labelCounts'][$label] = $GLOBALS['labelCounts'][$label] + 1;
        } else {
            $GLOBALS['labelCounts'][$label] = 1;
        }
    }

    public function getNumberOfSongs()
    {
        return count($GLOBALS['songs']);
    }

    public function setLabelProbabilities()
    {
        foreach (array_keys($GLOBALS['labelCounts']) as $label) {
            $numberOfSongs = $this->getNumberOfSongs();
            $GLOBALS['labelProbabilities'][$label] = $GLOBALS['labelCounts'][$label] / $numberOfSongs;
        }
    }

    public function setChordCountsInLabels()
    {
        foreach ($GLOBALS['songs'] as $i) {
            if (!isset($GLOBALS['chordCountsInLabels'][$i[0]])) {
                $GLOBALS['chordCountsInLabels'][$i[0]] = [];
            }
            foreach ($i[1] as $j) {
                if ($GLOBALS['chordCountsInLabels'][$i[0]][$j] > 0) {
                    $GLOBALS['chordCountsInLabels'][$i[0]][$j] = $GLOBALS['chordCountsInLabels'][$i[0]][$j] + 1;
                } else {
                    $GLOBALS['chordCountsInLabels'][$i[0]][$j] = 1;
                }
            }
        }
    }

    public function setProbabilityOfChordsInLabels()
    {
        $GLOBALS['probabilityOfChordsInLabels'] = $GLOBALS['chordCountsInLabels'];
        foreach (array_keys($GLOBALS['probabilityOfChordsInLabels']) as $i) {
            foreach (array_keys($GLOBALS['probabilityOfChordsInLabels'][$i]) as $j) {
                $GLOBALS['probabilityOfChordsInLabels'][$i][$j] = $GLOBALS['probabilityOfChordsInLabels'][$i][$j] * 1.0 / count($GLOBALS['songs']);
            }
        }
    }

    public function classify($chords)
    {
        $ttal = $GLOBALS['labelProbabilities'];
        print_r($ttal);
        $classified = [];
        foreach (array_keys($ttal) as $obj) {
            $first = $GLOBALS['labelProbabilities'][$obj] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $GLOBALS['probabilityOfChordsInLabels'][$obj][$chord];
                if (! isset($probabilityOfChordInLabel)) {
                    $first + 1.01;
                } else {
                    $first = $first * ($probabilityOfChordInLabel + 1.01);
                }
                $classified[$obj] = $first;
            }
        }
        print_r($classified);
    }
}