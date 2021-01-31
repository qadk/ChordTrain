<?php declare(strict_types=1);


class SongComplexityProbabilityCalculator
{
    private $songs = [];
    private $label_counts = [];
    private $chord_counts_in_labels = [];
    private $probability_of_label = [];
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
        $probabilities_of_label = $this->getProbabilitiesOfLabel();
        $classified = [];
        foreach ($probabilities_of_label as $label => $probability_of_label) {
            $probability = $probability_of_label + $this->probability_step;

            $classified[$label] = array_reduce($chords,
                $this->calculateChordInLabelProbability($this->probability_of_chords_in_labels[$label],
                    $this->probability_step),
                $probability);
        }

        return $classified;
    }

    public function getProbabilitiesOfLabel(): array
    {
        return $this->probability_of_label;
    }

    private function getNumberOfSongs(): int
    {
        return count($this->songs);
    }

    private function setProbabilities()
    {
        $number_of_songs = $this->getNumberOfSongs();

        foreach ($this->chord_counts_in_labels as $label => $chords) {
            $this->probability_of_label[$label] = $this->label_counts[$label] / $number_of_songs;
            foreach ($chords as $chord => $count) {
                $this->probability_of_chords_in_labels[$label][$chord] = $count / $number_of_songs;
            }
        }
    }

    /**
     * @param array $chords_count
     * @param float $step
     * @return Closure
     */
    private function calculateChordInLabelProbability(array $chords_count, float $step): Closure
    {
        return function ($probability, $chord) use ($chords_count, $step) {
            if (isset($chords_count[$chord])) {
                $probability *= $chords_count[$chord] + $step;
            }
            return $probability;
        };
    }
}