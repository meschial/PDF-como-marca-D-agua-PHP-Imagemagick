<?php
use Ajaxray\PHPWatermark\Watermark;

include __DIR__.'/../vendor/autoload.php';
include __DIR__.'/functions.php';

$watermark = new Watermark(__DIR__ . '/pdf/g_digital.pdf');

// Watermark with text
$watermark->setFont('Arial');
$watermark->setFontSize(15);
$watermark->setOpacity(0.3);//0.0 a 1
$watermark->setRotate(360); //0 a 360
$watermark->setOffsetName(25, 50); //Deslocamento X e Y em relação ao setPosition
$watermark->setOffsetCpf(25, 25); //Deslocamento X e Y em relação ao setPosition
$watermark->setPosition(Watermark::POSITION_BOTTOM_RIGHT);


if($_POST['nome'] <> '' & $_POST['cpf'] <> ''){
   $nome = "Nome: {$_POST['nome']}";
   $cpf  = "Cpf: {$_POST['cpf']}";
}else{
   header('Location: ../index.php');
}

if($watermark->withText($nome, $cpf, __DIR__ . '/pdf/novopdf.pdf')){

   $path = __DIR__."/pdf/novopdf.pdf";
   return download($path);
}