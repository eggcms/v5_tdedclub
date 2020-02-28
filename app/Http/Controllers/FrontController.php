<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Blog;
use App\Analyze;
use App\Tstep;
use App\Youtube;
use App\User;
class FrontController extends Controller
{
	public function index() {
		$news = new Blog;
		$last_news = $news->orderBy('id','desc')->first();
		$news = $news->orderBy('id','desc')->where('id','!=',$news->pluck('id')->last())->take(4)->get();

		$anas = new Analyze;
		$analyzes = $anas->orderBy('id','desc')->take(6)->get();

		$ts = new Tstep;
		$tstepsx = $ts->orderBy('id','asc')->where('updated_at','>',date("Y-m-d 06:00:00"))->take(8)->get();

		$json = file_get_contents('https://zeanza.com/mm88fa-api/vision_data/api.php?met=hdp&APIkey=S09ZWFArak1BZTNpcUZGNTA2YWVia2tjU0F0bUVyazNZdjJVSGpZWXJMcDlrWHFYRGNnYlRjTWphaFg1RUVVWGh6WjNsUDZ6WUJKeDlCYUFRZzdrenc9PTo6G5mkISD1Nfndtt7QHBsBSA==');
		$objs = json_decode($json);

		$you = new Youtube;
		$yous = $you->orderBy('id','desc')->take(2)->get();

		$max_tstep=$tstepsx->count();
        $dataxSet = [];
        if ($max_tstep > 0) {
			foreach($tstepsx as $ttsx) {
				$av = User::where('id',$ttsx->uid)->first();
				if ($ttsx->team1w == 0) { $ttsx->team1w='black'; }
				elseif ($ttsx->team1w == 1) { $ttsx->team1w='red'; }
				elseif ($ttsx->team1w == 2) { $ttsx->team1w='#00CC00'; }
				if ($ttsx->team2w == 0) { $ttsx->team2w='black'; }
				elseif ($ttsx->team2w == 1) { $ttsx->team2w='red'; }
				elseif ($ttsx->team2w == 2) { $ttsx->team2w='#00CC00'; }
				if ($ttsx->team3w == 0) { $ttsx->team3w='black'; }
				elseif ($ttsx->team3w == 1) { $ttsx->team3w='red'; }
				elseif ($ttsx->team3w == 2) { $ttsx->team3w='#00CC00'; }
				$dataxSet[] = [
					"id"=> $ttsx->id,
					"uid"=> $ttsx->uid,
					"team1"=> $ttsx->team1,
					"team2"=> $ttsx->team2,
					"team3"=> $ttsx->team3,
					"team1w"=> $ttsx->team1w,
					"team2w"=> $ttsx->team2w,
					"team3w"=> $ttsx->team3w,
					"created_at"=> $ttsx->created_at,
                    "updated_at"=> $ttsx->updated_at,
					"avatar"=> $av->avatar,
					"facebook"=> $av->facebook,
					"line"=> $av->line
				];
			}
			$mm = (8 - $max_tstep);
			for($i=1;$i<=$mm;$i++){
				$dataxSet[] = ["id"=> '',"uid"=> '',"team1"=> '',"team2"=> '',"team3"=> '',"team1w"=> '',"team2w"=> '',"team3w"=> '',"created_at"=> '',"updated_at"=> '',"avatar"=>'no-avatar.jpg',"line"=>'',"facebook"=>''];
			}
		}
		else {
			for($i=1;$i<=8;$i++){
				$dataxSet[] = ["id"=> '',"uid"=> '',"team1"=> '',"team2"=> '',"team3"=> '',"team1w"=> '',"team2w"=> '',"team3w"=> '',"created_at"=> '',"updated_at"=> '',"avatar"=>'no-avatar.jpg',"line"=>'',"facebook"=>''];
			}
		}

		return view('pages.user.home',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง',
			'meta_description'=>'ทีเด็ดคลับดอทคอม ศูนย์รวมทีเด็ดบอลสเต็ป ข้อมูลบอลจากลีกดังทั่วโลก โดยมุ่งเน้นข้อมูลที่ถูกต้อง ฉับไวเที่ยงตรง โดยบรรดากูรู ระดับเซียนในวงการลูกหนัง',
			'last_news'=>$last_news,
			'news'=>$news,
			'analyzes'=>$analyzes,
			'objs'=>$objs,
            'youtubes'=>$yous,
            'tstepsx'=>$dataxSet
		]);
	}

    public function allvicrow() {
        $ans = new Analyze;
        $analyzes = $ans->orderBy('id','desc')->get();
        return view('pages.user.allvicrow',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม วิเคราะห์บอลเด็ด จากลีกดังต่างๆ ทั่วโลก',
			'meta_description'=>'วิเคราะห์ทีมบอล แบบเที่ยงตรง ข้อมูลแน่นๆ ฟันธงแบบเป๊ะๆ โดยกูรูขั้นเทพในวงการ',
			'analyzes'=>$analyzes
		]);
    }

    public function allnews() {
        $ns = new Blog;
        $news = $ns->orderBy('id','desc')->get();
        return view('pages.user.allnews',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก',
			'meta_description'=>'ทีเด็ดคลับดอทคอม ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก เที่ยงตรง กระชับ ฉับไว',
			'allnews'=>$news
		]);
    }

    public function news($id) {
		visit($id);
        $ns = new Blog;
        $news = $ns->where('id',$id)->first();
        $news_update = $ns->orderBy('id','desc')->where('id','!=',$news->id)->take(5)->get();
        return view('pages.user.news',[
			'news'=>$news
		],[
			'news_update'=>$news_update
		]);
    }

    public function vview($id) {
		visit($id,'c','analyze');
        $an = new Analyze;
        $ans = $an->where('id',$id)->first();
        $ans_update = $ans->orderBy('id','desc')->where('id','!=',$ans->id)->take(5)->get();
        return view('pages.user.vview',['ans'=>$ans],['ans_update'=>$ans_update]);
    }

    public function fullpage() {
        $you = new Youtube;
        $yous = $you->orderBy('id','desc')->take(2)->get();
        $json = file_get_contents('https://zeanza.com/mm88fa-api/vision_data/api.php?met=stp&APIkey=S09ZWFArak1BZTNpcUZGNTA2YWVia2tjU0F0bUVyazNZdjJVSGpZWXJMcDlrWHFYRGNnYlRjTWphaFg1RUVVWGh6WjNsUDZ6WUJKeDlCYUFRZzdrenc9PTo6G5mkISD1Nfndtt7QHBsBSA==');
        $objs = json_decode($json);
        return view('pages.user.tdstep-page',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม ราคาบอลสเต็ปเดี่ยว',
			'meta_description'=>'ทีเด็ดคลับดอทคอม ราคาบอลสเต็ปเดี่ยว ประจำวันนี้',
			'objs'=>$objs,
			'youtubes'=>$yous
		]);
    }

    public function fullpage2() {
        $you = new Youtube;
        $yous = $you->orderBy('id','desc')->take(2)->get();
        $json = file_get_contents('https://zeanza.com/mm88fa-api/vision_data/api.php?met=hdp&APIkey=S09ZWFArak1BZTNpcUZGNTA2YWVia2tjU0F0bUVyazNZdjJVSGpZWXJMcDlrWHFYRGNnYlRjTWphaFg1RUVVWGh6WjNsUDZ6WUJKeDlCYUFRZzdrenc9PTo6G5mkISD1Nfndtt7QHBsBSA==');
        $objs = json_decode($json);
        return view('pages.user.tdstep-page2',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม ราคาบอลสเต็ป',
			'meta_description'=>'ทีเด็ดคลับดอทคอม ราคาบอลสเต็ป ประจำวันนี้',
			'objs'=>$objs,
			'youtubes'=>$yous
		]);
    }

    public function lineNotify(Request $request) {
        $message='name: '.$request->fullname.' mobile: '.$request->phone.' LineID: '.$request->lineid;
        // tdedclub token: E85WI8wJ3xDUBlxLR0xGl9zOeep3TseAQMmyKA4kJw0
        $token = 'E85WI8wJ3xDUBlxLR0xGl9zOeep3TseAQMmyKA4kJw0';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, "message=$message");
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        $headers = array( "Content-type: application/x-www-form-urlencoded", "Authorization: Bearer $token", );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec( $ch );
        curl_close( $ch );
        return Redirect()->back();
	}

    public function liveball() {
		//$embed = ball_table();
        return view('pages.user.live-page',[
			'meta_title'=>'ทีเด็ดคลับดอทคอม ดูบอลบสด ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก',
			'meta_description'=>'ทีเด็ดคลับดอทคอม ดูบอลบสด ศูนย์รวมข่าวสารวงการบอล จากลีกดังทั่วโลก เที่ยงตรง กระชับ ฉับไว',
		]);
	}
}
