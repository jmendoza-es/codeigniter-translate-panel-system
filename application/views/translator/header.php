<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="language" content="spanish">
<title><?php echo $page_title; ?></title>
<!--
<link rel=stylesheet href="/static/style.css" type="text/css" /><style type="text/css">
-->

<style>

.translator_table_header { font-size: 1.5em; font-weight: bold; background: none; border-bottom: 1px solid  #D0D0D0; }
.translator_error { color: #f00; font-weight: bold; }
.translator_note { color: #0f0; font-weight: bold; }
.sortable th { cursor:pointer; }
.sortable tr td:first-child { position:relative; padding-left:50px; } 
.sortable tr .actions { 
    position: absolute;
	cursor:pointer;
    width: 50px;
    left: 0;
    display: none;
    text-align: center;
    font-size: 25px;
    color: #b3b3b3;
}
.sortable tr:hover .actions { display:block; }
.sortable th.active:after { font-family:'FontAwesome'; content:" \f0d8"; }
.sortable th.active.up:after { font-family:'FontAwesome'; content:" \f0dd"; }
.sortable th:after {     font-family: 'FontAwesome';
    content: "\f0b0";
    font-size: 14px;
    padding: 0px 10px;
    display: block;
    float: left;
    margin-top: 10px;
    width: 35px;
}
mark{
    background-color: orange !important;
    color: white;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" integrity="sha384-pjaaA8dDz/5BgdFUPX6M/9SUZv4d12SUPF0axWc+VRZkx5xU3daN+lYb49+Ax+Tl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>

</head>
<body>

<div class="jumbotron jumbotron-fluid">
	<div class="container">
	  <h1 class="display-4"><?php echo $page_title ?></h1>
	  <p>Sistema interno de traducción, elige una de las siguientes opciones.</p>
	  <?php if($page_title != "Selecciona idioma") { ?> <p><a class="btn btn-primary btn-lg" href="/translator" role="button">Cambiar idioma »</a></p> <?php } ?>
	</div>
</div>

<div class="container">
<div class="row">