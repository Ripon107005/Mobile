
<?php
$brands = isset($_GET['b']) ? json_decode(urldecode($_GET['b'])) : array();
?>
<style>
    /* Style.css */
    * {
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: #ffffff;
        flex-direction: column;
    }

    .main {
        background-color: #fff;
        margin-top: 5px;
        box-shadow: 0 0 20px
        rgba(0, 0, 0, 0.2);
        padding: 20px;
        transition: transform 0.2s;
        width: 100%
    }

    .gfg {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
        color: #01940b;
    }

    .custom-wrapper {
        margin: 0;
        width: 100%;
        position: relative;
    }

    .header h2 {
        font-size: 30px;
        color: #01940b;
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .price-input-container {
        width: 100%;
    }

    .price-input .price-field {
        display: flex;
        margin-bottom: 22px;
    }

    .price-field span {
        margin-right: 10px;
        font-size: 15px;
    }

    .price-field input {
        flex: 1;
        height: 35px;
        width: 100%;
        font-size: 15px;
        font-family: "DM Sans", sans-serif;
        border-radius: 9px;
        text-align: center;
        border: 0px;
        background: #e4e4e4;
    }

    .price-input {
        width: 100%;
        font-size: 19px;
        color: #555;
    }

    /* Remove Arrows/Spinners */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .slider-container {
        width: 100%;
    }

    .slider-container {
        height: 6px;
        position: relative;
        background: #e4e4e4;
        border-radius: 5px;
    }

    .slider-container .price-slider {
        height: 100%;
        left: 25%;
        right: 15%;
        position: absolute;
        border-radius: 5px;
        background: #01940b;
    }

    .range-input {
        position: relative;
    }

    .range-input input {
        position: absolute;
        width: 100%;
        height: 5px;
        background: none;
        top: -5px;
        pointer-events: none;
        cursor: pointer;
        -webkit-appearance: none;
    }

    /* Styles for the range thumb in WebKit browsers */
    input[type="range"]::-webkit-slider-thumb {
        height: 18px;
        width: 18px;
        border-radius: 70%;
        background: #555;
        pointer-events: auto;
        -webkit-appearance: none;
    }

    @media screen and (max-width: 768px) {
        .main {
            width: 80%;
            margin-right: 5px;
        }

        .custom-wrapper {
            width: 100%;
            left: 0;
            padding: 0 10px;
        }

        .projtitle {
            width: 100%;
            position: relative;
            right: 26px;
        }

        .price-input {
            flex-direction: column;
            align-items: center;
        }

        .price-field {
            margin-bottom: 10px;
        }
    }

</style>
<section class="py-1">
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 px-1 border-right text-sm position-sticky ">
            <h4><b>Brands</b></h4>
            <ul class="list-group">
                <a href="" class="list-group-item list-group-item-action">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="brandAll" >
                        <label for="brandAll">
                             All
                        </label>
                    </div>
                </a>
                <?php 
                $qry = $conn->query("SELECT * FROM brands where status =1 order by name asc");
                $minMaxData = $conn->query("SELECT MIN(price) AS minPrice,MAX(price) AS maxPrice FROM `inventory`")->fetch_assoc();
                $minPrice = $minMaxData['minPrice'];
                $maxPrice = $minMaxData['maxPrice'];

                while($row=$qry->fetch_assoc()):
                ?>
                <li class="list-group-item list-group-item-action">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="brand-item-<?php echo $row['id'] ?>" <?php echo in_array($row['id'],$brands) ? "checked" : "" ?> class="brand-item" value="<?php echo $row['id'] ?>">
                        <label for="brand-item-<?php echo $row['id'] ?>">
                                <?php echo $row['name'] ?>
                        </label>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
            <div class="main">

                <div class="custom-wrapper">

                    <div class="price-input-container">
                        <div class="price-input">
                            <div class="price-field">
                                <span>Minimum Price</span>
                                <input type="number"
                                       class="min-input"
                                       value="<?=$minPrice?>">
                            </div>
                            <div class="price-field">
                                <span>Maximum Price</span>
                                <input type="number"
                                       class="max-input"
                                       value="<?=$maxPrice?>">
                            </div>
                        </div>
                        <div class="slider-container">
                            <div class="price-slider">
                            </div>
                        </div>
                    </div>

                    <!-- Slider -->
                    <div class="range-input">
                        <input type="range"
                               class="min-range"
                               min="0"
                               max="10000"
                               value="2500"
                               step="1">
                        <input type="range"
                               class="max-range"
                               min="0"
                               max="10000"
                               value="8500"
                               step="1">
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-10 py-2">
            <div class="row">
                <div class="col-md-12">
                    <div id="carouselExampleControls" class="carousel slide bg-dark" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php 
                                $upload_path = "uploads/banner";
                                if(is_dir(base_app.$upload_path)): 
                                $file= scandir(base_app.$upload_path);
                                $_i = 0;
                                    foreach($file as $img):
                                        if(in_array($img,array('.','..')))
                                            continue;
                                $_i++;
                                    
                            ?>
                            <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
                                <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100  h-100" alt="<?php echo $img ?>">
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        </div>
                </div>
            </div>
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-4 row-cols-md-3 row-cols-xl-4 ">
                    <?php
                        $where = "";
                        if(count($brands)>0)
                        $where = " and p.brand_id in (".implode(",",$brands).") " ;
                        if ($_GET['minPrice'] || $_GET['minPrice']){
                            $where='AND inventory.price BETWEEN '.$_GET['minPrice'].' AND '.$_GET['minPrice'].'';
                        }
                        $products = $conn->query("SELECT p.*,b.name as bname FROM `products` p inner join brands b on p.brand_id = b.id
                           JOIN inventory ON inventory.product_id = p.id
                           where p.status = 1 {$where} order by rand() ");
                        while($row = $products->fetch_assoc()):
                            $upload_path = base_app.'/uploads/product_'.$row['id'];
                            $img = "";
                            if(is_dir($upload_path)){
                                $fileO = scandir($upload_path);
                                if(isset($fileO[2]))
                                    $img = "uploads/product_".$row['id']."/".$fileO[2];
                                // var_dump($fileO);
                            }
                            foreach($row as $k=> $v){
                                $row[$k] = trim(stripslashes($v));
                            }
                            $inventory = $conn->query("SELECT * FROM inventory where product_id = ".$row['id']);
                            $inv = array();
                            while($ir = $inventory->fetch_assoc()){
                                $inv[] = number_format($ir['price']);
                            }
                    ?>
                    <div class="col mb-5">
                        <a class="card product-item text-dark" href=".?p=view_product&id=<?php echo md5($row['id']) ?>">
                            <!-- Product image-->
                            <img class="card-img-top w-100 book-cover" src="<?php echo validate_image($img) ?>" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder"><?php echo $row['name'] ?></h5>
                                    <!-- Product price-->
                                    <?php foreach($inv as $k=> $v): ?>
                                        <span><b>Price: </b><?php echo $v ?> TK</span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="m-0"><small>Brand: <?php echo $row['bname'] ?></small></p>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script>
    function _filter(){
        var brands = []
            $('.brand-item:checked').each(function(){
                brands.push($(this).val())
            })
        let minPrice = $('.min-input').val()
        let maxPrice = $('.max-input').val()
        _b = JSON.stringify(brands)
        var checked = $('.brand-item:checked').length
        var total = $('.brand-item').length
        if(checked == total)
            location.href="./?";
        else
            location.href="./?b="+_b+'&minPrice='+minPrice+'&maxPrice='+maxPrice;
    }
    function check_filter(){
        var checked = $('.brand-item:checked').length
        var total = $('.brand-item').length
        if(checked == total){
            $('#brandAll').attr('checked',true)
        }else{
            $('#brandAll').attr('checked',false)
        }
        if('<?php echo isset($_GET['b']) ?>' == '')
            $('#brandAll,.brand-item').attr('checked',true)
    }
    $(function(){
        check_filter()
        $('#brandAll').change(function(){
            if($(this).is(':checked') == true){
                $('.brand-item').attr('checked',true)
            }else{
                $('.brand-item').attr('checked',false)
            }
            _filter()
        })
        $('.brand-item').change(function(){
            _filter()
        })
    })

</script>
<script>
    // Script.js
    const rangevalue =
        document.querySelector(".slider-container .price-slider");
    const rangeInputvalue =
        document.querySelectorAll(".range-input input");

    // Set the price gap
    let priceGap = 500;

    // Adding event listners to price input elements
    const priceInputvalue =
        document.querySelectorAll(".price-input input");
    for (let i = 0; i < priceInputvalue.length; i++) {
        priceInputvalue[i].addEventListener("input", e => {

            // Parse min and max values of the range input
            let minp = parseInt(priceInputvalue[0].value);
            let maxp = parseInt(priceInputvalue[1].value);
            let diff = maxp - minp

            if (minp < 0) {
                alert("minimum price cannot be less than 0");
                priceInputvalue[0].value = 0;
                minp = 0;
            }

            // Validate the input values
            if (maxp > 10000) {
                alert("maximum price cannot be greater than 10000");
                priceInputvalue[1].value = '<?=$maxPrice?>';
                maxp = '<?=$maxPrice?>';
            }

            if (minp > maxp - priceGap) {
                priceInputvalue[0].value = maxp - priceGap;
                minp = maxp - priceGap;

                if (minp < 0) {
                    priceInputvalue[0].value = '<?=$minPrice?>';
                    minp = '<?=$minPrice?>';
                }
            }

            // Check if the price gap is met
            // and max price is within the range
            if (diff >= priceGap && maxp <= rangeInputvalue[1].max) {
                if (e.target.className === "min-input") {
                    rangeInputvalue[0].value = minp;
                    let value1 = rangeInputvalue[0].max;
                    rangevalue.style.left = `${(minp / value1) * 100}%`;
                }
                else {
                    rangeInputvalue[1].value = maxp;
                    let value2 = rangeInputvalue[1].max;
                    rangevalue.style.right =
                        `${100 - (maxp / value2) * 100}%`;
                }
            }
        });

        // Add event listeners to range input elements
        for (let i = 0; i < rangeInputvalue.length; i++) {
            rangeInputvalue[i].addEventListener("input", e => {
                let minVal =
                    parseInt(rangeInputvalue[0].value);
                let maxVal =
                    parseInt(rangeInputvalue[1].value);

                let diff = maxVal - minVal

                // Check if the price gap is exceeded
                if (diff < priceGap) {

                    // Check if the input is the min range input
                    if (e.target.className === "min-range") {
                        rangeInputvalue[0].value = maxVal - priceGap;
                    }
                    else {
                        rangeInputvalue[1].value = minVal + priceGap;
                    }
                }
                else {

                    // Update price inputs and range progress
                    priceInputvalue[0].value = minVal;
                    priceInputvalue[1].value = maxVal;
                    rangevalue.style.left =
                        `${(minVal / rangeInputvalue[0].max) * 100}%`;
                    rangevalue.style.right =
                        `${100 - (maxVal / rangeInputvalue[1].max) * 100}%`;
                }
            });
        }
    }

    document.querySelector('.min-range').addEventListener('input',function (){
        let minProductPrice = document.querySelector('.min-input').value
        let maxProductPrice = document.querySelector('.max-input').value

        setTimeout(function (){
            location.href=`./?minPrice=${minProductPrice}&maxPrice=${maxProductPrice}`;
        },1000)

    })
    document.querySelector('.max-range').addEventListener('input',function (){
        let minProductPrice = document.querySelector('.min-input').value
        let maxProductPrice = document.querySelector('.max-input').value

        setTimeout(function (){
            location.href=`./?minPrice=${minProductPrice}&maxPrice=${maxProductPrice}`;
        },1000)



    })



</script>