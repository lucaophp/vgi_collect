<?php

namespace App\Http\Controllers;

use App\Event;
use \App\TypeEv;
use \App\EventRep;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Session;

class HomeController extends Controller
{
    public function index()
    {
        $type = TypeEv::all();
        return view('welcome', ['type' => $type]);
    }

    //API
    public function marcar(Request $request)
    {
        $input = $request->all();
        $user = session()->get('user');
        $ev = new EventRep($input);
        if(session()->has('user')){

            $ev->user_id = $user->id;
        }


        return ['status' => $ev->save()];
    }

    //API
    public function photo(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = time() . '.' . $request->photo->getClientOriginalExtension();
        $request->photo->move(public_path('images'), $image);
        $url = 'images/' . $image;
        return response()->json(['url' => $url]);
    }

    //API
    public function getPoints()
    {
        $points = EventRep::orderBy('datahora', 'desc')->with('typeEv')->get();

        return ['points' => $points, 'status' => true];
    }
    //API - GET
    public function neighbor(Request $request)
    {
        $active = $request->route()->hasParameter('active');

        $circle_radius = 3959;
        $max_distance = 2000;
        $lat = $request->latitude;
        $lng = $request->longitude;
        $status = ($active)?'ACTIVED':'REPORTED';

        return $candidates = DB::select(
            'SELECT * FROM
                    (SELECT e.latitude, e.longitude,te.name,group_concat(e.id) ids,count(*) as qtd,(' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $lng . ')) +
                    sin(radians(' . $lat . ')) * sin(radians(latitude))))
                    AS distance
                    FROM event_rep as e INNER JOIN type_ev as te ON te.id = e.type_ev_id
                    WHERE e.status = \''.$status.'\'
                    GROUP BY e.latitude,e.longitude,te.name
                    ) AS distances
                WHERE distance < ' . $max_distance . '
                ORDER BY distance,qtd
                LIMIT 20;
            ');
    }
    public function deleteEvent(Request $request){
        $input = $request->all();
        $lon = $input['longitude'];
        $lat = $input['latitude'];
        $type = $input['type'];
        $ids = explode(',',$input['ids']);
        $type_obj = TypeEv::query('name','like',$type)->first();

        return EventRep::whereIn('id',$ids)->forceDelete();

    }

    public function finallyEvent(Request $request){
        $input = $request->all();
        $ids = explode(',',$input['ids']);
        $resp = Event::whereIn('event_rep_id',$ids)->forceDelete();
        EventRep::whereIn('id',$ids)->update(['status'=>'FINISHED']);
        return ['status'=>$resp];
    }
    public function getEvent(Request $request){
        $input = $request->all();
        $ids = explode(',',$input['ids']);
        return Event::whereIn('event_rep_id',$ids)->first();

    }
    public function updateEvent(Request $request){
        $input = $request->all();
        $user = session()->get('user');
        $id = $input['id'];
        $ev = Event::find($id);
        $ev->obs = $input['descricao'];
        $estimate = $input['estimate'];
        $now=new \DateTime();
        $now->add(new \DateInterval("P0000-00-00T$estimate:00"));
        $ev->estimative = $now;
        $ev->level = $input['level'];
        $ev->user = $user;
        return ['status'=>$ev->save()];
    }
    public function confirmEvent(Request $request){
        $input = $request->all();
        $desc = $input['descricao'];
        $ids = explode(',',$input['ids']);
        $level = $input['level'];
        $estimate = $input['estimate'];
        $now=new \DateTime();
        $now->add(new \DateInterval("P0000-00-00T$estimate:00"));

        EventRep::whereIn('id',$ids)->update(['status'=>'ACTIVED']);
        $user = session()->get('user');
        $ev = new Event([
            'event_rep_id'=>$ids[0],
            'user_id' => $user->id,
            'estimative'=>$now,
            'accept'=>true,
            'obs'=>$desc,
            'level'=>$level
        ]);
        return ['status'=>$ev->save()];

    }
    public function login(){
        return view('login');
    }
    public function validaLogin(Request $request){
        $input = $request->all();
        $email = $input['email'];
        $pass = $input['password'];
        $users = User::where(['email'=>$email,'password'=>$pass])->get();
        $qtd = count($users);
        if($qtd==0){
            Session::flash('error','Login Incorreto!!!');
            return redirect()->back();
        }else{
            $request->session()->put('user',$users[0]);
            return redirect()->to('admin');
        }
    }
    public function admin(){
        if(session()->has('user')){
            return view('admin');
        }
        Session::flash('error','Acesso Restrito!!!');
        return redirect()->to('login');
    }
    public function logout(){
        session()->forget('user');
        return redirect()->to('/');
    }
    public function register(){
        return view('register');
    }
    public function registerSave(Request $request){
        $user = new User($request->all());
        $users = User::where(['email'=>$request->all()['email']])->get();
        if(count($users)>0){
            Session::flash('error','E-Mail existente!!!');
            return redirect()->back();
        }
        try{
            $res = $user->saveOrFail();
            return redirect()->to('login');
        }catch (Exception $e){
            Session::flash('error','Não foi possivel cadastrar!!! Favor verifique os campos.');
            return redirect()->back();
        }


    }
    public function getDataBySession(){
        $user = session()->get('user');
        if($user==null){
            return ['status'=>false,'user'=>[]];
        }
        return ['status'=>true,'user'=>$user];
    }
    public function address(Request $request){
        $input = $request->all();
        $lat = $input['lat'];
        $lng = $input['lng'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,  CURLOPT_URL, 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='.$lat.'&lon='.$lng);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");

        $data = curl_exec($ch);

        curl_close($ch);
        $dados = json_decode($data);
        return array($dados);

    }
    public function forgetPassword(Request $request){
        $email = $request->all()['email'];
        $user = User::where('email',$email)->get();
        if(count($user)>0){
            $newPass = $user[0]->generateRandomString();
            $user[0]->password = $newPass;

            try{
                mail($email,'nao-responda@ufv.br','Sua senha para recuperação é: '.$newPass);
                $user[0]->save();
            }catch (ErrorException $e){

            }

            Session::flash('error','Os dados de recuperação foram enviados para seu e-mail.');
            return redirect()->back();
        }else{
            Session::flash('error','E-Mail não está cadastrado!!!');
            return redirect()->back();

        }

    }
    public function updateUser(Request $request){
        if(!session()->has('user')){
            Session::flash('error','Acesso Restrito!!!');
            return redirect()->back();
        }
        $user = session()->get('user');
        $input = $request->all();
        $id = trim($input['id']);
        $name = trim($input['name']);
        $pass = trim($input['password']);
        $user->name = $name;
        $user->password = ($pass=='')?$user->password:$pass;
        $user->save();
        Session::flash('info','Usuario Modificado Com Sucesso!!!');
        return redirect()->to('admin');

    }
    public function updateUserForm(Request $request){
        if(!session()->has('user')){
            Session::flash('error','Acesso Restrito!!!');
            return redirect()->back();
        }
        $user = session()->get('user');
        return view('updateRegister',['user'=>$user]);

    }

}
