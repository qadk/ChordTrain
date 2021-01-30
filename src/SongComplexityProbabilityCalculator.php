<?php declare(strict_types=1);


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
        $this->label_counts[$label] += 1;
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
        foreach ($this->songs as $song) {
            $label = $song[0];
            foreach ($song[1] as $chord) {
                $this->chord_counts_in_labels[$label][$chord] += 1;
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