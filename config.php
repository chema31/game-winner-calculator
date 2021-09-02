<?php
/**
 * Storage all configuration data
 */
define('INPUT_DIR', 'game_tables');
define('FILE_EXCEPTIONS', ['.','..','.DS_Store']);

//CSV parser configuration
define('CSV_MAX_LENGTH', 1000);
define('CSV_DELIMITER', ';');
define('ROW_GAME_NAME', 0);
define('COL_GAME_NAME', 0);
define('NUM_COLS_GAME', 6);
define('NUM_COLS_LOL', 10);