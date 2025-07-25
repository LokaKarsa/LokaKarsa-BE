<?php
// config/gamification.php

return [
  /*
    |--------------------------------------------------------------------------
    | Level Calculation Constant
    |--------------------------------------------------------------------------
    |
    | Konstanta ini mengontrol seberapa cepat pengguna naik level.
    | Semakin kecil nilainya, semakin banyak XP yang dibutuhkan untuk naik level.
    | Formula: Level = floor(level_constant * sqrt(XP))
    |
    */
  'level_constant' => 0.05,
];
