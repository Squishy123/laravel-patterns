<?php
// Quick example of how we could make use of DTOs and VOs

// create a DTO for initial data coming in
class CreateAuctionLotDto
{
    public function __construct(
        public string $auctionId, //required params come first
        public string $inventoryItemId,
        public string $inventoryItemGroupId,
        public string $projectId,
        public string $number,
        public string $title,
        public string $condition,
        public string $description = '', //optional params follow
        public string $brand = '',
        public string $model = '',
        public string $internalReference = '',
        public string $internalLocation = '',
        public int $length = 0,
        public int $width = 0,
        public int $height = 0,
        public int $weight = 0,
        public int $starting = 0,
        public int $removal = 0,
        public int $reserve = 0,
        public int $estimatedSellingPrice = 0,
        public int $bin = 0,
        public string $loadingMethod = '',
        public string $shippingMethod = ''
    ) {
    }
}

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
