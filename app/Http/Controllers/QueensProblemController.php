<?php

namespace App\Http\Controllers;

class QueensProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return false|\Illuminate\Http\Response|string
     */
    public function index()
    {

        // There can be only one queen per rank or file.
        // The position in the array can be seen as the
        // rank, the number as the file. The solution set consists
        // only of permutations of this array.

        $board_size = 7;
        $board_ranks = range(1, $board_size);

        /**
         * These can be subtracted from the possible options.
         * Only add constraints for the ranks to the right,
         * because the ones to the left are already taken care off
         *
         * @param int $rank
         * @param int $file
         * @param array $constraints
         * @param int $board_size
         * @return array
         */
        function add_constraints(int $rank, int $file, $constraints = [], int $board_size = 7): array
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

        // Array to keep account of all solutions stil in contention,
        // and the constraints for the following ranks.
        // For the first rank, all files are still possible
        $solution_paths = array_map(static function ($x) {
            return [[$x], add_constraints(1, $x)];
        }, $board_ranks);

        for ($i = 2; $i <= $board_size; $i++) {

            $solution_paths_extended = [];
            $rank_nr = $i;

            foreach ($solution_paths as $solution_path) {

                $constraints = $solution_path[1];
                $possible_continuations = array_diff($board_ranks, $constraints[$rank_nr]);

                foreach ($possible_continuations as $file) {

                    $new_path = $solution_path[0];
                    $new_path[] = $file;

                    // If this is not the final solution, keep track of
                    // the constraints
                    if ($i < $board_size) {

                        $solution_paths_extended[] = [
                            $new_path,
                            add_constraints($rank_nr, $file, $constraints)
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

        $solution_boards = [];
        $fen_solutions = [];

        foreach ($solution_paths as $solution) {

            $solution_boards = [];
            // Start with an empty row, since we have only a 7x7 board
            $fen_board = '8/';
            $ranks_number = count($solution);

            foreach ($solution as $iValue) {

                $row = array_fill(0, $ranks_number, 0);
                $row[$iValue-1] = 'Q';
                $solution_board[] = $row;

                $iValue--;
                $fen_start = $iValue !== 0 ? $iValue : '';
                $fen_row = $fen_start . 'Q' . (7-$iValue) . '/';
                $fen_board .= $fen_row;
            }

            // add an empty line
            $fen_solutions[] = substr($fen_board,0, -1);
            $solution_boards[] = $solution_board;
        }

        return view('queens_solution.queens_solution', compact('fen_solutions'));
    }
}
