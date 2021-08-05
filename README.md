# Wishlist app

This example contains my solution to a code challenge. It consists of:
1. A simple wishlist app:
    - A small shop created from two sources of products available via APIs, in which it is possible to select and deselect items.
    - A wishlist pages that shows the selected items and has the possibility to delete them again.
    - The frontend is not too important, since the focus was on the backend (Laravel / PHP)
    - Since we don't have user login etc, the wishlist is shared.
- A solution to the 7 Queens Puzzle (similar to [The 8 Queens Puzzle](https://en.wikipedia.org/wiki/Eight_queens_puzzle#:~:text=The%20eight%20queens%20puzzle%20is,row%2C%20column%2C%20or%20diagonal.
))
    - Solved in the backend. I used [Chris Oakman's Javascript Chessboard](https://chessboardjs.com/) to display the solutions on a 8x8 chessboard.
    
The end result can be seen at:
[Goods and Shoes Shop](http://188.166.12.222/shop)

### I have also dockerized the app
Start with:
```
./vendor/bin/sail up
```
from wishlist-app container terminal:
```
php artisan migrate
```

If already running, stop and clean all data:
```
./vendor/bin/sail down --rmi all -v
```

