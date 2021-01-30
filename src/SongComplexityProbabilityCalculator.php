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
        $this->songs[] = ['label' => $label, 'chords' => $chords];
        $this->label_counts[$label] += 1;
    }

    public function getNumberOfSongs(): int
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
            foreach ($song['chords'] as $chord) {
                $this->chord_counts_in_labels[$song['label']][$chord] += 1;
            }
        }
    }

    public function setProbabilityOfChordsInLabels()
    {
        foreach ($this->chord_counts_in_labels as $label => $chords) {
            foreach ($chords as $chord => $count) {
                $this->probability_of_chords_in_labels[$label][$chord] = $count * 1.0 / $this->getNumberOfSongs();
            }
        }
    }

    public function classify($chords)
    {
        $label_probabilities = $this->label_probabilities;
        print_r($label_probabilities);
        $classified = [];
        foreach ($label_probabilities as $label => $probability) {
            $label_probability = $probability + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probability_of_chords_in_labels[$label][$chord];
                if (isset($probabilityOfChordInLabel)) {
                    $label_probability = $label_probability * ($probabilityOfChordInLabel + 1.01);
                }
                $classified[$label] = $label_probability;
            }
        }
        print_r($classified);
    }
}