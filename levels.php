<?php
function generateRandomLetters($length)
{
    $letters = "";
    $possibleLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    for ($i = 0; $i < $length; $i++) {
        $letters .= $possibleLetters[rand(0, strlen($possibleLetters) - 1)];
    }

    return $letters;
}

function generateRandomNumbers($length)
{
    $numbers = "";
    $possibleNumbers = "0123456789";

    for ($i = 0; $i < $length; $i++) {
        $numbers .= $possibleNumbers[rand(0, strlen($possibleNumbers) - 1)];
    }

    return $numbers;
}

function sortLettersDescending($letters)
{
    $sortedLetters = str_split($letters);

    usort($sortedLetters, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return implode('', array_reverse($sortedLetters));
}

function sortLettersAscending($letters)
{
    $sortedLetters = str_split($letters);

    usort($sortedLetters, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return implode('', $sortedLetters);
}

function sortNumbersAscending($numbers)
{
    $sortedNumbers = str_split($numbers);

    usort($sortedNumbers, function ($a, $b) {
        return $a - $b;
    });

    return implode('', $sortedNumbers);
}

function sortNumbersDescending($numbers)
{
    $sortedNumbers = str_split($numbers);

    usort($sortedNumbers, function ($a, $b) {
        return $a - $b;
    });

    return implode('', array_reverse($sortedNumbers));
}

$levels = [
    [
        'title' => "Niveau de jeu 1 - Arrangez 6 lettres dans l’ordre croissant",
        'randomLetters' => generateRandomLetters(6),
        'numInputBoxes' => 6,
        'expectedAnswer' => sortLettersAscending(generateRandomLetters(6)),
    ],
    [
        'title' => "Niveau de jeu 2 - Arrangez 6 lettres dans l’ordre décroissant",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Niveau de jeu 3 - Arrangez 6 nombres dans l’ordre croissant",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Niveau de jeu 4 - Arrangez 6 nombres dans l’ordre décroissant",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Niveau de jeu 5 - Identifier la première et la dernière lettre d’un ensemble de 6 lettres",
        'randomLetters' => '',
        'numInputBoxes' => 2,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Niveau de jeu 6 - Identifier le plus petit nombre et le plus grand nombre d’un ensemble de 6 nombres",
        'randomLetters' => '',
        'numInputBoxes' => 2,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
];

foreach ($levels as &$level) {
    if (strpos($level['title'], "Niveau de jeu 1") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $level['expectedAnswer'] = sortLettersAscending($level['randomLetters']);
    } elseif (strpos($level['title'], "Niveau de jeu 2") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $level['expectedAnswer'] = sortLettersDescending($level['randomLetters']);
    } elseif (strpos($level['title'], "Niveau de jeu 3") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $level['expectedAnswer'] = sortNumbersAscending($level['randomLetters']);
    } elseif (strpos($level['title'], "Niveau de jeu 4") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $level['expectedAnswer'] = sortNumbersDescending($level['randomLetters']);
    } elseif (strpos($level['title'], "Niveau de jeu 5") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $randomLettersArray = str_split($level['randomLetters']);
        $randomLettersArray = array_map('strtolower', $randomLettersArray); // Convert letters to lowercase
        sort($randomLettersArray, SORT_STRING); // Sort the letters in ascending order
        $level['expectedAnswer'] = $randomLettersArray[0] . $randomLettersArray[count($randomLettersArray) - 1];
    } elseif (strpos($level['title'], "Niveau de jeu 6") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $randomLettersArray = str_split($level['randomLetters']);
        $level['expectedAnswer'] = min($randomLettersArray) . max($randomLettersArray);
    }
}
return $levels;
?>