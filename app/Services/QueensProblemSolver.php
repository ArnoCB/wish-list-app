<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class QueensProblemSolver
{
    /**
     * Store in cache to avoid unnecessary recalculations.
     *
     * @return array    The solutions in pairs of FEN 8x8 and FEN 7x7 solutions
     */
    public function solveQueensProblem(): array
    {
        return Cache::rememberForever('solutionsInFen', function () {

            return $this->generateSolutions();
        });
    }

    /**
     * Return all solutions for the 7x7 Queens problem in an
     * array containing both the 8x8 and 7x7 Fen codes for all solutions.
     *
     * @return array
     */
    private function generateSolutions(): array
    {
        // There can be only one queen per rank or file.
        // The position in the array can be seen as the
        // rank, the number as the file. The solution set consists
        // only of permutations of this array.

        $board_size = 7;
        $board_ranks = range(1, $board_size);

        // Array to keep account of all solutions stil in contention,
        // and the constraints for the following ranks.
        // For the first rank, all files are still possible.
        $solution_paths = array_map(
            static fn($x) => [[$x], $this->addConstraints(1, $x, [], $board_size)], $board_ranks
        );

        for ($i = 2; $i <= $board_size; $i++) {

            $solution_paths_extended = [];
            $rank_nr = $i;

            foreach ($solution_paths as $solution_path) {

                $constraints = $solution_path[1];

                // Find out on which files we can still put a queen, without
                // being on a diagonal that already contains one.
                // These are all files, minus the ones ruled out with the
                // help of the add_constraints() function.
                $possible_continuations = array_diff($board_ranks, $constraints[$rank_nr]);

                foreach ($possible_continuations as $file) {

                    $new_path = $solution_path[0];
                    $new_path[] = $file;

                    // If this is not the final solution, keep track of
                    // the constraints. If not, only keep the solutions.
                    $solution_paths_extended[] = ($i < $board_size)
                        ? [$new_path, $this->addConstraints($rank_nr, $file, $constraints, $board_size)]
                        : $new_path;
                }
            }

            $solution_paths = $solution_paths_extended;
        }

        return $this->createFenSolutions($solution_paths);
    }

    /**
     * Make a list of files where the queen can not be placed for a certain rank.
     * Only add constraints for the ranks to the right,
     * because the ones to the left are already taken care off.
     *
     * @param int $rank
     * @param int $file
     * @param array $constraints    the new constraints will be added to this
     *                              array already containing constraints found earlier.
     * @param int $board_size
     * @return array                an array with all constraints
     */
    private function addConstraints(int $rank, int $file, array $constraints = [], int $board_size = 7): array
    {
        for ($i = $rank + 1; $i <= $board_size; $i++) {

            if (!isset($constraints[$i])) {

                $constraints[$i] = [];
            }

            if (!in_array($file, $constraints[$i], true)) {

                // this file cannot be used again
                $constraints[$i][] = $file;
            }

            $diagonal_up = $file + ($i - $rank);
            $diagonal_down = $file - ($i - $rank);

            if ($diagonal_up <= $board_size && !in_array($diagonal_up, $constraints[$i], true)) {

                $constraints[$i][] = $diagonal_up;
            }

            if ($diagonal_down > 0 && !in_array($diagonal_down, $constraints[$i], true)) {

                $constraints[$i][] = $diagonal_down;
            }
        }

        return $constraints;
    }

    /**
     * Convert the solutions into Forsyth-Edwards Notation (FEN)
     *
     * @param $solution_paths
     * @return array
     */
    private function createFenSolutions($solution_paths): array
    {
        $fen_solutions = [];

        foreach ($solution_paths as $solution) {

            // Start with an empty row, since we have to display a 7x7 solution on a 8x8 board
            $fen_board8 = '8/';

            // Also produce the fen code for a 7x7 board. This makes the symmetries easier
            // to spot.
            $fen_board7 = '';

            foreach ($solution as $iValue) {

                // generate the 8x8 fen code for this solution, so
                // the solutions can be displayed on a chessboard.
                $iValue--;
                $fen_start = $iValue !== 0 ? $iValue : '';
                $fen_end = (6-$iValue) !== 0 ? (6-$iValue) : '';

                $fen_row7 = $fen_start . 'Q' . $fen_end . '/';
                $fen_row8 = $fen_start . 'Q' . (7-$iValue) . '/';

                $fen_board7 .= $fen_row7;
                $fen_board8 .= $fen_row8;
            }

            // remove the unnecessary slash at the end of the string
            $fen_solutions[] = [substr($fen_board8,0, -1),
                                substr($fen_board7,0, -1)];
        }

        return $fen_solutions;
    }
}
