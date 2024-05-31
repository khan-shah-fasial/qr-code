<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;


class IndexController extends Controller
{

    public function qr_gen($qrdigit){

        return QrCode::size(150)
        ->backgroundColor(0, 0, 225)
        ->color(255, 255, 0)
        ->margin(1)
        ->generate(
            ''.$qrdigit.'',
        );

    }


    public function create_bulk_tag_qr(Request $request)
	{
	    $vyapari_id = $request->vyapari_id;

       $data = DB::table('app_qrcode')->where('vyapari_id', $vyapari_id)->get(['qrcode']);

        //    echo"<pre>";
        //    var_dump($data);
        //    echo"</pre>";
	    
	    if($data->isNotEmpty() && count($data) > 0 && !empty($data) && $data != null){
	        
	        foreach($data as $row){
	            
	            // $tag_no = $this->db->select(['certificate_no','tag_no'])->from('cattle_pre_booking')->where('certificate_no', $row->qrcode)->get()->row();

                $tag_no = DB::table('cattle_pre_booking')->where('certificate_no', $row->qrcode)->get(['certificate_no','tag_no'])->first();
	            
	            if($tag_no || !empty($tag_no)){
	                
                    //    echo"<pre>";
                    //    var_dump($tag_no);
                    //    echo"</pre>";
	                
	                $qrdigit = $tag_no->tag_no;
	                $certificate = $tag_no->certificate_no;
	                
	                $qr = $this->qr_gen($qrdigit);

                    echo $qr;

                    echo '<p style="margin: 0;position: absolute;left: 11px;font-size: 16px;margin-top: -1px;"><b>Tag No: '.$qrdigit.'</b></p><br><p style="margin-left: 4px; margin-top: 17px;"><b>Cert :'.$certificate.'</b></p></div>';

	                
	            } else {
	                echo"<pre>";
	                echo"Tag no Not present or not Match with Qrcode";
	                echo"</pre>";
	            }
	            
	        }
	        
	    } else {
	        echo "DATA Not Present";
	    }
	    
	}


}