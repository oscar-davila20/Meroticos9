<?php 

ob_start();
session_start();

/*=============================================
Traer el dominio principal
=============================================*/

$path = TemplateController::path();

/*=============================================
Traer el total de productos
=============================================*/

$url = CurlController::api()."products?select=id_product";
$method = "GET";
$fields = array();
$header = array();

$totalProducts = CurlController::request($url, $method, $fields, $header)->total;




/*=============================================
Capturar las rutas de la URL
=============================================*/

$routesArray = explode("/", $_SERVER['REQUEST_URI']);

/*=============================================
Ajuste para Facebook
=============================================*/

if(!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])){

	if(!empty(array_filter($routesArray)[2])){

		$urlGet = explode("?", array_filter($routesArray)[2]);

		$urlParams = explode("&", $urlGet[0]);	
		
	}

}else{

	if(!empty(array_filter($routesArray)[1])){

		$urlGet = explode("?", array_filter($routesArray)[1]);

		$urlParams = explode("&", $urlGet[0]);		
		
	}

}

if(!empty($urlParams[0])){

	/*=============================================
    Filtrar categorías con el parámetro URL
    =============================================*/

    $url = CurlController::api()."categories?linkTo=url_category&equalTo=".$urlParams[0]."&select=url_category";
    $method = "GET";
    $fields = array();
    $header = array();

    $urlCategories = CurlController::request($url, $method, $fields, $header);
    
    if($urlCategories->status == 404){

    	/*=============================================
	    Filtrar subcategorías con el parámetro URL
	    =============================================*/

	    $url = CurlController::api()."subcategories?linkTo=url_subcategory&equalTo=".$urlParams[0]."&select=url_subcategory";
	    $method = "GET";
	    $fields = array();
	    $header = array();

	    $urlSubCategories = CurlController::request($url, $method, $fields, $header);
	    
	   	if($urlSubCategories->status == 404){

	   		/*=============================================
		    Filtrar productos con el parámetro URL
		    =============================================*/

		    $url = CurlController::api()."products?linkTo=url_product&equalTo=".$urlParams[0]."&select=url_product";
		    $method = "GET";
		    $fields = array();
		    $header = array();

		    $urlProduct = CurlController::request($url, $method, $fields, $header);

		    if($urlProduct->status == 404){

		    	/*=============================================
				Validar si hay parámetros de paginación
				=============================================*/
				if(isset($urlParams[1])){

					if(is_numeric($urlParams[1])){


						$startAt = ($urlParams[1]*6) - 6;

					}else{

					 	$startAt = null;

					}

				}else{

					$startAt = 0;
				}

				/*=============================================
				Validar si hay parámetros de orden
				=============================================*/

				if(isset($urlParams[2])){

					if(is_string($urlParams[2])){

						if($urlParams[2] == "new"){

							$orderBy = "id_product";
							$orderMode = "DESC";

						}

						else if($urlParams[2] == "latest"){

							$orderBy = "id_product";
							$orderMode = "ASC";

						}

						else if($urlParams[2] == "low"){

							$orderBy = "price_product";
							$orderMode = "ASC";

						}

						else if($urlParams[2] == "high"){

							$orderBy = "price_product";
							$orderMode = "DESC";

						}else{

							$orderBy = "id_product";
							$orderMode = "DESC";

						}

					}else{
						
						$orderBy = "id_product";
						$orderMode = "DESC";

					}

				}else{

					$orderBy = "id_product";
					$orderMode = "DESC";
				}


				$linkTo = ["name_product","title_list_product","tags_product","summary_product"];
				$select = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_category,name_category,id_category,views_subcategory,name_subcategory,id_subcategory,summary_product";

				foreach ($linkTo  as $key => $value) {

					/*=============================================
			    	Filtrar tabla producto con el parámetro URL de búsqueda
			    	=============================================*/

				    $url = CurlController::api()."relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=".$value.",approval_product,state_product&search=".$urlParams[0].",approved,show&orderBy=".$orderBy."&orderMode=".$orderMode."&startAt=".$startAt."&endAt=6&select=".$select;
				    $method = "GET";
				    $fields = array();
				    $header = array();

				    $urlSearch = CurlController::request($url, $method, $fields, $header);
				   
				
					if($urlSearch->status != 404){

						$totalSearch = $urlSearch->total;

						break;

					}
				
				}

			}

	   	}

    }


}


?>   


<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

	<title>MarketPlace | Home</title>

    <base href="views/">

    <link rel="icon" href="img/template/icono.png">   

	<!--=====================================
	CSS
	======================================-->
	
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&display=swap" rel="stylesheet">

	<!-- font awesome -->
	<link rel="stylesheet" href="css/plugins/fontawesome.min.css">

	<!-- linear icons -->
	<link rel="stylesheet" href="css/plugins/linearIcons.css">

	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/plugins/owl.carousel.css">

	<!-- Slick -->
	<link rel="stylesheet" href="css/plugins/slick.css">

	<!-- Light Gallery -->
	<link rel="stylesheet" href="css/plugins/lightgallery.min.css">

	<!-- Font Awesome Start -->
	<link rel="stylesheet" href="css/plugins/fontawesome-stars.css">

	<!-- jquery Ui -->
	<link rel="stylesheet" href="css/plugins/jquery-ui.min.css">

	<!-- Select 2 -->
	<link rel="stylesheet" href="css/plugins/select2.min.css">

	<!-- Scroll Up -->
	<link rel="stylesheet" href="css/plugins/scrollUp.css">
    
    <!-- DataTable -->
    <link rel="stylesheet" href="css/plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/plugins/responsive.bootstrap.datatable.min.css">

    <!-- Placeholder-loading -->
    <!-- https://github.com/zalog/placeholder-loading -->
    <!-- https://www.youtube.com/watch?v=JU_sklV_diY -->
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading@0.2.6/dist/css/placeholder-loading.min.css">

    <!-- Notie Alert -->
    <link rel="stylesheet" href="css/plugins/notie.min.css">

    <!-- include summernote css/js -->
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

	<!-- tags Input -->
	<link rel="stylesheet" href="css/plugins/tagsinput.css">

	<!-- Dropzone -->
	<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
	
	<!-- estilo principal -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Market Place 4 -->
	<link rel="stylesheet" href="css/market-place-4.css">

	<!--=====================================
	PLUGINS JS
	======================================-->

	<!-- jQuery library -->
	<script src="js/plugins/jquery-1.12.4.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<!-- Owl Carousel -->
	<script src="js/plugins/owl.carousel.min.js"></script>

	<!-- Images Loaded -->
	<script src="js/plugins/imagesloaded.pkgd.min.js"></script>

	<!-- Masonry -->
	<script src="js/plugins/masonry.pkgd.min.js"></script>

	<!-- Isotope -->
	<script src="js/plugins/isotope.pkgd.min.js"></script>

	<!-- jQuery Match Height -->
	<script src="js/plugins/jquery.matchHeight-min.js"></script>

	<!-- Slick -->
	<script src="js/plugins/slick.min.js"></script>

	<!-- jQuery Barrating -->
	<script src="js/plugins/jquery.barrating.min.js"></script>

	<!-- Slick Animation -->
	<script src="js/plugins/slick-animation.min.js"></script>

	<!-- Light Gallery -->
	<script src="js/plugins/lightgallery-all.min.js"></script>
    <script src="js/plugins/lg-thumbnail.min.js"></script>
    <script src="js/plugins/lg-fullscreen.min.js"></script>
    <script src="js/plugins/lg-pager.min.js"></script>

	<!-- jQuery UI -->
	<script src="js/plugins/jquery-ui.min.js"></script>

	<!-- Sticky Sidebar -->
	<script src="js/plugins/sticky-sidebar.min.js"></script>

	<!-- Slim Scroll -->
	<script src="js/plugins/jquery.slimscroll.min.js"></script>

	<!-- Select 2 -->
	<script src="js/plugins/select2.full.min.js"></script>

	<!-- Scroll Up -->
	<script src="js/plugins/scrollUP.js"></script>

    <!-- DataTable -->
    <script src="js/plugins/jquery.dataTables.min.js"></script>
    <script src="js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="js/plugins/dataTables.responsive.min.js"></script>

    <!-- Chart -->
    <script src="js/plugins/Chart.min.js"></script>
	
	 <!-- pagination -->
	<!-- http://josecebe.github.io/twbs-pagination/ -->
    <script src="js/plugins/twbs-pagination.min.js"></script>

     <!-- md5 -->
    <script src="js/plugins/md5.min.js"></script>

    <!-- Notie Alert -->
    <!-- https://jaredreich.com/notie/ -->
    <!-- https://github.com/jaredreich/notie -->
    <script src="https://unpkg.com/notie@4.3.1/dist/notie.min.js"></script>

    <!-- Sweet Alert -->
    <!-- https://sweetalert2.github.io/ -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- PayPal -->
    <!-- https://developer.paypal.com/docs/checkout/ -->
    <script src="https://www.paypal.com/sdk/js?client-id=[YOUR CLIENT ID]"></script>

    
    <!-- Mercado Pago -->
    <!-- https://www.mercadopago.com.co/developers/es/guides/online-payments/checkout-api/guide-mercadopagojs-v2 -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <!-- summernote -->
    <!-- https://summernote.org/getting-started/#run-summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <!-- Tags Input -->
    <!-- https://www.jqueryscript.net/form/Bootstrap-4-Tag-Input-Plugin-jQuery.html -->
    <script src="js/plugins/tagsinput.js"></script>

    <!-- Dropzone -->
    <!-- https://www.dropzonejs.com/ -->
    <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>

    <script src="js/head.js"></script>
	
</head>

<body>

  
	<!--=====================================
	Header Promotion
	======================================-->

	<?php include "modules/top-banner.php" ?>

     <!--=====================================
    Header
    ======================================-->

    <?php include "modules/header.php" ?> 

    <!--=====================================
    Header Mobile
    ======================================-->

   <?php include "modules/header-mobile.php" ?>

    <!--=====================================
    Pages
    ======================================-->
	
	<?php 

	if(!empty($urlParams[0])){

		if($urlParams[0] == "account" || $urlParams[0] == "shopping-cart" || $urlParams[0] == "checkout"){

			include "pages/".$urlParams[0]."/".$urlParams[0].".php";	

		}else if($urlCategories->status == 200 || $urlSubCategories->status == 200 ){

			include "pages/products/products.php";

		}else if($urlProduct->status == 200){

			include "pages/product/product.php";

		}else if($urlSearch->status == 200){

		   include "pages/search/search.php";

		}else{

			include "pages/404/404.php";

		}

	}else{

		include "pages/home/home.php";

	}


	?>  

   

    <!--=====================================
	Newletter
	======================================-->  

    <?php include "modules/newletter.php" ?>

    <!--=====================================
	Footer
	======================================-->  
    
    <?php include "modules/footer.php" ?>

	<!--=====================================
	JS PERSONALIZADO
	======================================-->

	<script src="js/main.js"></script>
	
</body>
</html>