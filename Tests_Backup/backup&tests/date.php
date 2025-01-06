<?php

class Date
{
    private int $j;    
    private int $m;    
    private int $a;    
    private int $h;    
    private int $min;  

    // Constructor 
    public function __construct(int $u = 1, int $v = 1, int $w = 2000, int $x = 0, int $y = 0);

    // sh7al mn nhar fshher (30/31/28)
    private function daysInMonth(int $month): int;

    // surcharge de ==
    public function equals(Date $d): bool;

    // surcharge de >
    public function isGreaterThan(Date $d): bool;

    // surcharge input
    public function input(): void;

    // surcharge output
    public function __toString(): string;
}

// Constructor definition
public function Date::__construct(int $u, int $v, int $w, int $x, int $y)
{
    $this->j = $u;
    $this->m = $v;
    $this->a = $w;
    $this->h = $x;
    $this->min = $y;
}

// Days in a month definition 
private function Date::daysInMonth(int $month): int
{
    switch ($month) {
        case 1: case 3: case 5: case 7: case 8: case 10: case 12:
            return 31;
        case 4: case 6: case 9: case 11:
            return 30;
        case 2:
            return 28; // (29 fevrier isha3a)
        default:
            return 0;
    }
}

// surcharge de == definition
public function Date::equals(Date $d): bool
{
    return (
        $this->j === $d->j &&
        $this->m === $d->m &&
        $this->a === $d->a &&
        $this->h === $d->h &&
        $this->min === $d->min
    );
}

// surcharge de > definition
public function Date::isGreaterThan(Date $d): bool
{
    if ($this->a > $d->a) return true;
    if ($this->a < $d->a) return false;

    if ($this->m > $d->m) return true;
    if ($this->m < $d->m) return false;

    if ($this->j > $d->j) return true;
    if ($this->j < $d->j) return false;

    if ($this->h > $d->h) return true;
    if ($this->h < $d->h) return false;

    return $this->min > $d->min;
}

// surcharge Input definition 
public function Date::input(): void
{
    echo "Enter date (day month year hour minute): ";
    $input = trim(fgets(STDIN)); // Get input from the user
    $inputArray = explode(' ', $input);

    if (count($inputArray) !== 5 || !ctype_digit(implode('', $inputArray))) {
        echo "Error: Please enter a valid date in the format 'day month year hour minute'.\n";
        return;
    }

    [$day, $month, $year, $hour, $minute] = array_map('intval', $inputArray);

    if (
        $month < 1 || $month > 12 ||
        $day < 1 || $day > $this->daysInMonth($month) ||
        $hour < 0 || $hour > 23 ||
        $minute < 0 || $minute > 59
    ) {
        echo "Error: Invalid date or time.\n";
    } else {
        $this->j = $day;
        $this->m = $month;
        $this->a = $year;
        $this->h = $hour;
        $this->min = $minute;
    }
}

// surcharge Output definition
public function Date::__toString(): string
{
    return sprintf("%02d/%02d/%04d %02d:%02d", $this->j, $this->m, $this->a, $this->h, $this->min);
}





