<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TestingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Testing Cron is Running ... !");
        \DB::connection()->enableQueryLog();
        $products = Product::selectRaw('lp.PID,products.id,lp.pro_name,products.name,pm.pro_img,thumb_image')->join('live_products as lp','products.name','=','lp.pro_name')->join('product_image as pm','pm.PID','=','lp.PID')->get();
            \Log::info(\DB::getQueryLog());
            if(!empty($products)){
                foreach($products as $val){
                    if($val->pro_img!=''){
                        $exp = array_filter(explode(',',$val->pro_img));
                        \Log::info(json_encode($exp));
                        if(!empty($exp)){
                            $j=0;
                            foreach($exp as  $k=>$v){
                                if(File::exists(public_path('/uploads/product_img/'.$v))){
                                    // \Log::info(public_path('/uploads/product_img/'.$v));
                                    if($j==0 && $val->thumb_image=='uploads/product_img/no-image.png'){
                                        $pro = Product::find($val->id);
                                        $pro->thumb_image = 'uploads/product_img/'.$v;
                                        $pro->save();
                                        $j++;
                                    }
                                    $pg = new ProductGallery();
                                    $pg->product_id  = $val->id;
                                    $pg->image = 'uploads/product_img/'.$v;
                                    $pg->save();
                                }else{
                                    // \Log::info('fail');

                                }


                        }
                    }
                    }

                }
            }

            // \Log::info($products);
        /*
           Write your database logic we bellow:
           User::create(['email'=>'send mail']);
        */

        $this->info('testing:cron Command Run Successfully !');
    }
}
