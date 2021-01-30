<?php declare(strict_types=1);


class SongComplexityProbabilityCalculator
{
    private $songs = [];
    private $label_counts = [];
    private $label_probabilities = [];
    private $chord_counts_in_labels = [];
    private $probability_of_chords_in_labels = [];
    private $probability_step = 1.01;

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

    public function getLabelProbabilities(): array
    {
        return $this->label_probabilities;
    }

    public function classify($chords): array
    {
        $label_probabilities = $this->label_probabilities;
        $classified = [];
        foreach ($label_probabilities as $label => $probability) {
            $label_probability = $probability + $this->probability_step;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probability_of_chords_in_labels[$label][$chord];
                if (isset($probabilityOfChordInLabel)) {
                    $label_probability *= $probabilityOfChordInLabel + $this->probability_step;
                }
                $classified[$label] = $label_probability;
            }
        }

        return $classified;
    }
}