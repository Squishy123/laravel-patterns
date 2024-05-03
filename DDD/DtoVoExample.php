<?php
// Quick example of how we could make use of DTOs and VOs

class ADto
{
    public $setOptionalVars = [];

    public function __construct(...$values)
    {
        // apply dynamic values
        foreach ($values as $k => $v) {
            if (!property_exists($this, $k)) {
                continue;
            }
            $this->$k = $v;
            $this->setOptionalVars[$k] = $v;
        }
    }

    public function __get($property)
    {
        if (isset($this->$property) && isset($this->setOptionalVars[$property])) {
            return $this->$property;
        }
        trigger_error('Undefined property ' . $property . '.', E_USER_WARNING);
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function properties()
    {
        $ref = new ReflectionClass($this);
        return array_map(fn ($p) => $p->name, $ref->getProperties());
    }


    public function valuesArr()
    {
        $values = [];
        foreach ($this->properties() as $property) {
            if (!isset($this->{$property}) && !isset($this->setOptionalVars[$property])) {
                continue;
            }
            $values[$property] = $this->{$property};
        }
        unset($values['setOptionalVars']);
        return $values;
    }

    //return values that are set and valid
    public function values()
    {
        return (object) $this->valuesArr();
    }
}

// create a DTO for initial data coming in
class CreateAuctionLotDto extends ADto
{
    //optional vars here
    protected string $length, $width, $height, $weight, $starting, $removal, $reserve, $estimatedSellingPrice, $bin;
    protected string $description, $brand, $model, $internalReference, $internalLocation, $loadingMethod, $shippingMethod;

    public function __construct(
        public string $auctionId, //required params come first
        public string $inventoryItemId,
        public string $inventoryItemGroupId,
        public string $projectId,
        public string $number,
        public string $title,
        public string $condition,
        ...$values, //optional params are here
    ) {
        parent::__construct(...$values);

        //perform validation
    }
}

$b = new CreateAuctionLotDto(
    auctionId: 5,
    inventoryItemId: 1,
    inventoryItemGroupId: 1,
    projectId: 1,
    number: "AbcD",
    title: "Auction Lot 1",
    condition: "N",
    bin: 'test',
    brand: 'hello world'
);

//just a sample class
class Auction
{
}

// create a validator VO to check for rules
class CreateAuctionLotVo
{
    public Auction $auction;
    public $optional; //optional value
    public $initialized = 5; //initialized value

    public function __construct(private CreateAuctionLotDto $createAuctionLotDto) //private visibility so we only focus on what fields differ in this VO) 
    {
        //validate against rules
        if ($createAuctionLotDto->auctionId != 5) { //come up with a fake rule here 
            throw new Exception("Auction does not exist");
        }

        //we can initialize other fields that we didn't originally have
        $this->auction = new Auction();
    }
}

$createAuctionLotDto = new CreateAuctionLotDto(
    auctionId: 5,
    inventoryItemId: 1,
    inventoryItemGroupId: 1,
    projectId: 1,
    number: "AbcD",
    title: "Auction Lot 1",
    condition: "N"
);

echo json_encode($createAuctionLotDto) . "\n";

$createAuctionLotVo = new CreateAuctionLotVo(
    createAuctionLotDto: $createAuctionLotDto
);

echo json_encode($createAuctionLotVo) . "\n";

// lets try to create one where validation should fail
try {
    $createAuctionLotDto = new CreateAuctionLotDto(
        auctionId: 2,
        inventoryItemId: 1,
        inventoryItemGroupId: 1,
        projectId: 1,
        number: "AbcD",
        title: "Auction Lot 1",
        condition: "N"
    );

    $createAuctionLotVo = new CreateAuctionLotVo(
        createAuctionLotDto: $createAuctionLotDto
    );
} catch (Exception $e) { // it does fail
    echo 'Exception: ' . $e->getMessage() . "\n";
}

//we can do this all in one go
echo json_encode(new CreateAuctionLotVo(new CreateAuctionLotDto(
    auctionId: 5,
    inventoryItemId: 1,
    inventoryItemGroupId: 1,
    projectId: 1,
    number: "AbcD",
    title: "Auction Lot 1",
    condition: "N"
))) . "\n";


//example of a request call
function createAuctionLot(
    CreateAuctionLotDto $createAuctionLotDto //type safety check
): Auction {
    $createAuctionLotVo = new CreateAuctionLotVo($createAuctionLotDto);
    $createAuctionLotVo->auction; // do some processing here

    return $createAuctionLotVo->auction;
}
