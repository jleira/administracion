<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class pdfController extends Controller
{
  public function invoice()
  {
      $view =  \View::make('pdf.pdf')->with(array())->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('invoice');
  }

  public function getData()
  {
      $data =  [
          'quantity'      => '1' ,
          'description'   => 'some ramdom text',
          'price'   => '500',
          'total'     => '500'
      ];
      return $data;
  }
}
