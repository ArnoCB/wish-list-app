<?php
namespace App\Services;

class QueensProblemSolver
{
    /**
     * Return all solutions for the 7x7 Queens problem in an
     * array containing both the 8x8 and 7x7 Fen codes for all solutions
     *
     * @return array
     */
    public function solve_queens_problem(): array
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
        $solution_paths = array_map(static function ($x) {
            return [[$x], self::add_constraints(1, $x)];
        }, $board_ranks);

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
                    // the constraints.
                    if ($i < $board_size) {

                        $solution_paths_extended[] = [
                            $new_path,
                            self::add_constraints($rank_nr, $file, $constraints)
                        ];
                    }
                    else {

                        // If this is the final solution, only the solution is important
                        $solution_paths_extended[] = $new_path;
                    }
                }
            }

            $solution_paths = $solution_paths_extended;
        }

        $fen_solutions = [];

        foreach ($solution_paths as $solution) {

            // Start with an empty row, since we have display a 7x7 solution on a 8x8 board
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

                $fen_row = $fen_start . 'Q' . (7-$iValue) . '/';
                $fen_row7 = $fen_start . 'Q' . $fen_end . '/';

                $fen_board7 .= $fen_row7;
                $fen_board8 .= $fen_row;
            }

            // remove the unnecessary slash at the end of the string
            $fen_solutions[] = [substr($fen_board8,0, -1),
                                substr($fen_board7,0, -1)];
        }

        return $fen_solutions;
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
    private static function add_constraints(int $rank, int $file, array $constraints = [], int $board_size = 7): array
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
}
