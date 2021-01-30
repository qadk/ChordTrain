<?php



class SongComplexityProbabilityCalculator
{
    private $songs = [];
    private $label_counts = [];
    private $label_probabilities = [];
    private $chord_counts_in_labels = [];
    private $probability_of_chords_in_labels = [];

    public function train($chords, $label)
    {
        $this->songs[] = [$label, $chords];
        if (!!(in_array($label, array_keys($this->label_counts)))) {
            $this->label_counts[$label] = $this->label_counts[$label] + 1;
        } else {
            $this->label_counts[$label] = 1;
        }
    }

    public function getNumberOfSongs()
    {
        return count($this->songs);
    }

    public function setLabelProbabilities()
    {
        foreach (array_keys($this->label_counts) as $label) {
            $numberOfSongs = $this->getNumberOfSongs();
            $this->label_probabilities[$label] = $this->label_counts[$label] / $numberOfSongs;
        }
    }

    public function setChordCountsInLabels()
    {
        foreach ($this->songs as $i) {
            if (!isset($this->chord_counts_in_labels[$i[0]])) {
                $this->chord_counts_in_labels[$i[0]] = [];
            }
            foreach ($i[1] as $j) {
                if ($this->chord_counts_in_labels[$i[0]][$j] > 0) {
                    $this->chord_counts_in_labels[$i[0]][$j] = $this->chord_counts_in_labels[$i[0]][$j] + 1;
                } else {
                    $this->chord_counts_in_labels[$i[0]][$j] = 1;
                }
            }
        }
    }

    public function setProbabilityOfChordsInLabels()
    {
        $this->probability_of_chords_in_labels = $this->chord_counts_in_labels;
        foreach (array_keys($this->probability_of_chords_in_labels) as $i) {
            foreach (array_keys($this->probability_of_chords_in_labels[$i]) as $j) {
                $this->probability_of_chords_in_labels[$i][$j] = $this->probability_of_chords_in_labels[$i][$j] * 1.0 / count($this->songs);
            }
        }
    }

    public function classify($chords)
    {
        $ttal = $this->label_probabilities;
        print_r($ttal);
        $classified = [];
        foreach (array_keys($ttal) as $obj) {
            $first = $this->label_probabilities[$obj] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probability_of_chords_in_labels[$obj][$chord];
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