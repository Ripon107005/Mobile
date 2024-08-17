
<?php
$brands = isset($_GET['b']) ? json_decode(urldecode($_GET['b'])) : array();
?>
<style>
    .min-max-slider {position: relative; width: 200px; text-align: center; margin-bottom: 50px;}
    .min-max-slider > label {display: none;}
    span.value {height: 1.7em; font-weight: bold; display: inline-block;}
    span.value.lower::before {
        content: "Tk ";
        display: inline-block;
    }

    span.value.upper::before {
        content: "-Tk ";
        display: inline-block;
        margin-left: 0.4em;
    }
    .min-max-slider > .legend {display: flex; justify-content: space-between;}
    .min-max-slider > .legend > * {font-size: small; opacity: 0.25;}
    .min-max-slider > input {cursor: pointer; position: absolute;}

    /* webkit specific styling */
    .min-max-slider > input {
        -webkit-appearance: none;
        outline: none!important;
        background: transparent;
        background-image: linear-gradient(to bottom, transparent 0%, transparent 30%, silver 30%, silver 60%, transparent 60%, transparent 100%);
    }
    .min-max-slider > input::-webkit-slider-thumb {
        -webkit-appearance: none; /* Override default look */
        appearance: none;
        width: 14px; /* Set a specific slider handle width */
        height: 14px; /* Slider handle height */
        background: #eee; /* Green background */
        cursor: pointer; /* Cursor on hover */
        border: 1px solid gray;
        border-radius: 100%;
    }
    .min-max-slider > input::-webkit-slider-runnable-track {cursor: pointer;}
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

            <div class=" mt-1 custom-wrapper">
                <div class="min-max-slider" data-legendnum="2">
                    <label for="min">Minimum price</label>
                    <input id="min" class="min" name="min" type="range" step="1" min="<?= isset($_GET['minPrice']) && !empty($_GET['minPrice']) ? $_GET['minPrice']: $minPrice?>" max="<?=$maxPrice?>" />
                    <label for="max">Maximum price</label>
                    <input id="max" class="max" name="max" type="range" step="1" min="0" max="<?=isset($_GET['maxPrice']) && !empty($_GET['maxPrice']) ? $_GET['maxPrice']:$maxPrice?>" />
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
                        if (isset($_GET['minPrice']) || isset($_GET['minPrice'])){
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
    var thumbsize = 14;

    function draw(slider,splitvalue) {

        /* set function vars */
        var min = slider.querySelector('.min');
        var max = slider.querySelector('.max');
        var lower = slider.querySelector('.lower');
        var upper = slider.querySelector('.upper');
        var legend = slider.querySelector('.legend');
        var thumbsize = parseInt(slider.getAttribute('data-thumbsize'));
        var rangewidth = parseInt(slider.getAttribute('data-rangewidth'));
        var rangemin = parseInt(slider.getAttribute('data-rangemin'));
        var rangemax = parseInt(slider.getAttribute('data-rangemax'));

        /* set min and max attributes */
        min.setAttribute('max',splitvalue);
        max.setAttribute('min',splitvalue);

        /* set css */
        min.style.width = parseInt(thumbsize + ((splitvalue - rangemin)/(rangemax - rangemin))*(rangewidth - (2*thumbsize)))+'px';
        max.style.width = parseInt(thumbsize + ((rangemax - splitvalue)/(rangemax - rangemin))*(rangewidth - (2*thumbsize)))+'px';
        min.style.left = '0px';
        max.style.left = parseInt(min.style.width)+'px';
        min.style.top = lower.offsetHeight+'px';
        max.style.top = lower.offsetHeight+'px';
        legend.style.marginTop = min.offsetHeight+'px';
        slider.style.height = (lower.offsetHeight + min.offsetHeight + legend.offsetHeight)+'px';

        /* correct for 1 off at the end */
        if(max.value>(rangemax - 1)) max.setAttribute('data-value',rangemax);

        /* write value and labels */
        max.value = max.getAttribute('data-value');
        min.value = min.getAttribute('data-value');
        lower.innerHTML = min.getAttribute('data-value');
        upper.innerHTML = max.getAttribute('data-value');

    }

    function init(slider) {
        /* set function vars */
        var min = slider.querySelector('.min');
        var max = slider.querySelector('.max');
        var rangemin = parseInt(min.getAttribute('min'));
        var rangemax = parseInt(max.getAttribute('max'));
        var avgvalue = (rangemin + rangemax)/2;
        var legendnum = slider.getAttribute('data-legendnum');

        /* set data-values */
        min.setAttribute('data-value',rangemin);
        max.setAttribute('data-value',rangemax);

        /* set data vars */
        slider.setAttribute('data-rangemin',rangemin);
        slider.setAttribute('data-rangemax',rangemax);
        slider.setAttribute('data-thumbsize',thumbsize);
        slider.setAttribute('data-rangewidth',slider.offsetWidth);

        /* write labels */
        var lower = document.createElement('span');
        var upper = document.createElement('span');
        lower.classList.add('lower','value');
        upper.classList.add('upper','value');
        lower.appendChild(document.createTextNode(rangemin));
        upper.appendChild(document.createTextNode(rangemax));
        slider.insertBefore(lower,min.previousElementSibling);
        slider.insertBefore(upper,min.previousElementSibling);

        /* write legend */
        var legend = document.createElement('div');
        legend.classList.add('legend');
        var legendvalues = [];
        for (var i = 0; i < legendnum; i++) {
            legendvalues[i] = document.createElement('div');
            var val = Math.round(rangemin+(i/(legendnum-1))*(rangemax - rangemin));
            legendvalues[i].appendChild(document.createTextNode(val));
            legend.appendChild(legendvalues[i]);

        }
        slider.appendChild(legend);

        /* draw */
        draw(slider,avgvalue);

        /* events */
        min.addEventListener("input", function() {update(min);});
        max.addEventListener("input", function() {update(max);});
    }

    function update(el){
        /* set function vars */
        var slider = el.parentElement;
        var min = slider.querySelector('#min');
        var max = slider.querySelector('#max');
        var minvalue = Math.floor(min.value);
        var maxvalue = Math.floor(max.value);

        /* set inactive values before draw */
        min.setAttribute('data-value',minvalue);
        max.setAttribute('data-value',maxvalue);

        var avgvalue = (minvalue + maxvalue)/2;

        /* draw */
        draw(slider,avgvalue);
    }

    var sliders = document.querySelectorAll('.min-max-slider');
    sliders.forEach( function(slider) {
        init(slider);
    });
</script>
<script>
    document.querySelector('.min').addEventListener('input',function (){
        let minProductPrice = document.querySelector('.min').getAttribute('data-value')
        let maxProductPrice = document.querySelector('.max').getAttribute('data-value')

        setTimeout(function (){
            location.href=`./?minPrice=${minProductPrice}&maxPrice=${maxProductPrice}`;
        },1000)

    })
    document.querySelector('.max').addEventListener('input',function (){
        let minProductPrice = document.querySelector('.min').getAttribute('data-value')
        let maxProductPrice = document.querySelector('.max').getAttribute('data-value')

        setTimeout(function (){
            location.href=`./?minPrice=${minProductPrice}&maxPrice=${maxProductPrice}`;
        },1000)

    })
</script>