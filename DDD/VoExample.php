<?php

//VO's can be created using a PHP class 
class LunchMoneyVo
{
    // We can use the constructor to specify visibility, type, and field optionality 
    public function __construct(
        public string $name,
        public int $age,
        public int $lunchMoney,
    ) {
        //here we can provide checks for validation
        if ($age < 10 && $lunchMoney > 10) {
            throw new Exception("Too much money for someone under 10"); //issues with validation should fail at the exception level
        }
    }
}

//This will pass since the values pass validation
$chrisLunchMoney = new LunchMoneyVo(
    name: 'Chris',
    age: 23,
    lunchMoney: 100
);

echo json_encode($chrisLunchMoney) . "\n";

try {
    //This fails because Bob has too much money
    $bobLunchMoney = new LunchMoneyVo(
        name: 'Bob',
        age: 9,
        lunchMoney: 50
    );
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

