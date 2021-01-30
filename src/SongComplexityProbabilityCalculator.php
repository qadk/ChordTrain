<?php declare(strict_types=1);


class SongComplexityProbabilityCalculator
{
    private $songs = [];
    private $label_counts = [];
    private $probability_of_label = [];
    private $chord_counts_in_labels = [];
    private $probability_of_chords_in_labels = [];
    private $probability_step = 1.01;

    public function train($chords, $label)
    {
        $this->songs[] = ['label' => $label, 'chords' => $chords];
        $this->label_counts[$label] += 1;

        foreach ($chords as $chord) {
            $this->chord_counts_in_labels[$label][$chord] += 1;
        }

        $this->setProbabilities();
    }

    public function classify($chords): array
    {
        $label_probabilities = $this->getProbabilityOfLabel();
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

    public function getProbabilityOfLabel(): array
    {
        return $this->probability_of_label;
    }

    private function getNumberOfSongs(): int
    {
        return count($this->songs);
    }

    private function setProbabilities()
    {
        $numberOfSongs = $this->getNumberOfSongs();

        foreach ($this->chord_counts_in_labels as $label => $chords) {
            $this->probability_of_label[$label] = $this->label_counts[$label] / $numberOfSongs;
            foreach ($chords as $chord => $count) {
                $this->probability_of_chords_in_labels[$label][$chord] = $count / $numberOfSongs;
            }
        }
    }
}