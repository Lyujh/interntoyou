<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    //首页
    public function index()
    {
        $bankuai = [];
        $BankuaiRes = DB::table('bankuai')->where('father_id', 0)->get();
        foreach ($BankuaiRes as $BankuaiRow) {
            $BankuaiChildRes = DB::table('bankuai')->where('father_id', $BankuaiRow->bankuai_id)->get();
            foreach ($BankuaiChildRes as $BankuaiChildRow) {
                $childInfo = DB::table('tiezi')
                    ->join('user', 'tiezi.huifu_user', '=', 'user.huifu_user')
                    ->where('tiezi.bankuai_id', $BankuaiChildRow->bankuai_id)
                    ->orderBy('tiezi.create_time', 'desc')
                    ->select('tiezi.title', 'tiezi.create_time', 'user.user_name')
                    ->first();

                $childCount = DB::table('tiezi')
                    ->join('user', 'tiezi.huifu_user', '=', 'user.huifu_user')
                    ->where('tiezi.bankuai_id', $BankuaiChildRow->bankuai_id)
                    ->count();

                $BankuaiChildRow->count = $childCount;

                if ($childInfo) {
                    $BankuaiChildRow->lastTieziTitle = $childInfo->title;
                    $BankuaiChildRow->lastTieziUserName = $childInfo->user_name;
                    $BankuaiChildRow->lastTieziCreateTime = $childInfo->create_time;
                } else {
                    $BankuaiChildRow->lastTieziTitle = '';
                    $BankuaiChildRow->lastTieziUserName = '';
                    $BankuaiChildRow->lastTieziCreateTime = '';
                }
                $BankuaiRow->zbk[] = $BankuaiChildRow;
            }
            $bankuai[] = $BankuaiRow;
        }

        return view('index', ['bankuai' => $bankuai]);
    }

    //列表
    public function list(Request $request, $bankuai_id)
    {
        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "不要乱搞";
            die;
        }

        $list = DB::table('tiezi')
            ->leftJoin('user', 'tiezi.huifu_user', '=', 'user.huifu_user')
            ->leftJoin('huifu', 'tiezi.tie_id', '=', 'huifu.tie_id')
            ->where('tiezi.bankuai_id', $bankuai_id)
            ->groupBy('tiezi.tie_id')
            // ->orderBy('tiezi.create_time', 'desc')
            ->select('tiezi.*', 'user.user_name', DB::raw('count(huifu.huifu_id) as counts'))
            ->paginate(5);

        return view('list', ['list' => $list, 'bankuai' => $bankuai]);
    }

    //帖子列表
    public function tiezi(Request $request, $bankuai_id, $tie_id)
    {
        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "不要乱搞";
            die;
        }

        //执行帖子查询
        $tiezi = DB::table('tiezi')
            ->join('user', 'tiezi.huifu_user', '=', 'user.huifu_user')
            ->where('tiezi.tie_id', $tie_id)
            ->select('user.huifu_user', 'user.user_name', 'user.headimage', 'user.create_time as uctime', 'tiezi.tie_id', 'tiezi.title', 'tiezi.content', 'tiezi.create_time', 'tiezi.update_time')
            ->first();
        

        //执行回复列表查询
        $list = DB::table('huifu')
            ->join('user', 'huifu.huifu_user', '=', 'user.huifu_user')
            ->where('huifu.tie_id', $tie_id)
            ->select('user.huifu_user', 'user.user_name', 'user.headimage', 'user.create_time as uctime', 'huifu.huifu_id', 'huifu.content', 'huifu.create_time', 'huifu.update_time')
            ->paginate(4);

        return view('tiezi', ['tiezi' => $tiezi, 'list' => $list, 'bankuai' => $bankuai]);
    }

    //发帖
    public function fatie(Request $request, $bankuai_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "不要乱搞";
            die;
        }

        return view('fatie', ['bankuai' => $bankuai]);
    }

    //校验发帖
    public function doFatie(Request $request)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $title = $request->title;
        $content = $request->content;
        $bankuai_id = $request->bankuai_id;

        if ($title == NULL || $title == "") {
            return back()->withErrors('帖子标题不能为空!!!', 'titleMessage')->withInput();
        }

        if ($content == NULL || $content == "") {
            return back()->withErrors('内容不能为空!!!', 'contentMessage')->withInput();
        }

        if (mb_strlen($content) > 1000) {
            return back()->withErrors('内容长度不能大于1000', 'contentMessage')->withInput();
        }

        DB::table('tiezi')->insert([
                'title' => $title,
                'content' => $content,
                'huifu_user' => session('user_id'),
                'bankuai_id' => $bankuai_id,
                'create_time' => now(),
                'update_time' => now()
            ]);

        return redirect('list/' . $bankuai_id);
    }

    //删除帖子
    public function doTieziDelete(Request $request, $bankuai_id, $tie_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $tieziUserRow = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tieziUserRow) {
            echo "<a href= '/'> 少年 没有这个帖子奥! ! ! !</a>";
            die;
        }

        if (session('user_id') != $tieziUserRow->huifu_user) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        DB::table('tiezi')->where('tie_id', $tie_id)->delete();
        return redirect('list/' . $bankuai_id);
    }

    //修改帖子
    public function fatieEdit(Request $request, $bankuai_id, $tie_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "少年 没有这个板块奥";
            die;
        }

        $tiezi = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tiezi) {
            echo "少年 没有这个帖子奥";
            die;
        }

        return view('fatieEdit', ['bankuai' => $bankuai, 'tiezi' => $tiezi]);
    }

    //校验修改帖子
    public function doTieziEdit(Request $request)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $tie_id = $request->tie_id;
        $title = $request->title;
        $content = $request->content;
        $bankuai_id = $request->bankuai_id;

        if ($title == NULL || $title == "") {
            return back()->withErrors('帖子标题不能为空!!!', 'titleMessage')->withInput();
        }

        if ($content == NULL || $content == "") {
            return back()->withErrors('内容不能为空!!!', 'contentMessage')->withInput();
        }

        if (mb_strlen($content) > 1000) {
            return back()->withErrors('内容长度不能大于1000', 'contentMessage')->withInput();
        }

        $tieziUserRow = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tieziUserRow) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        if (session('user_id') != $tieziUserRow->huifu_user) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        DB::table('tiezi')->where('tie_id', $tie_id)->update([
                'title' => $title,
                'content' => $content,
                'update_time' => now()
            ]);

        return redirect('tiezi/' . $bankuai_id . '/' . $tie_id);
    }

    //回复帖子
    public function huifu(Request $request, $bankuai_id, $tie_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "少年 没有这个板块奥";
            die;
        }

        $tiezi = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tiezi) {
            echo "少年 没有这个帖子奥";
            die;
        }

        return view('huifu', ['bankuai' => $bankuai, 'tie_id' => $tie_id]);
    }

    //校验回复帖子
    public function doHuifu(Request $request)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $tie_id = $request->tie_id;
        $content = $request->content;
        $bankuai_id = $request->bankuai_id;

        if ($content == NULL || $content == "") {
            return back()->withErrors('回复内容不能为空!!!', 'contentMessage')->withInput();
        }

        if (mb_strlen($content) > 1000) {
            return back()->withErrors('回复内容长度不能大于1000', 'contentMessage')->withInput();
        }

        $tieziUserRow = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tieziUserRow) {
            echo "少年 没有这个帖子奥<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        DB::table('huifu')->insert([
                'content' => $content,
                'tie_id' => $tie_id,
                'huifu_user' => session('user_id'),
                'create_time' => now(),
                'update_time' => now()
            ]);

        return redirect('tiezi/' . $bankuai_id . '/' . $tie_id);
    }

    //删除帖子回复
    public function doHuifuDelete(Request $request, $huifu_id, $bankuai_id, $tie_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $HuiUserRow = DB::table('huifu')->where('huifu_id', $huifu_id)->first();
        if (!$HuiUserRow) {
            echo "<a href= '/'> 少年 没有这个帖子回复奥! ! ! !</a>";
            die;
        }

        if (session('user_id') != $HuiUserRow->huifu_user) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        DB::table('huifu')->where('huifu_id', $huifu_id)->delete();
        return redirect('tiezi/' . $bankuai_id . '/' . $tie_id);
    }

    //修改帖子回复
    public function huifuEdit(Request $request, $huifu_id, $bankuai_id, $tie_id)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $bankuai = DB::table('bankuai')->where('bankuai_id', $bankuai_id)->first();
        if (!$bankuai) {
            echo "少年 没有这个板块奥";
            die;
        }

        $tiezi = DB::table('tiezi')->where('tie_id', $tie_id)->first();
        if (!$tiezi) {
            echo "少年 没有这个帖子奥";
            die;
        }


        $huifu = DB::table('huifu')->where('huifu_id', $huifu_id)->first();
        if (!$huifu) {
            echo "少年 没有这个回复奥";
            die;
        }

        return view('huifuEdit', ['bankuai' => $bankuai, 'tie_id' => $tiezi->tie_id, 'huifu' => $huifu]);
    }

    //校验修改帖子回复
    public function doHuifuEdit(Request $request)
    {
        //如果未登录，则跳转至登录页面
        if (!session('user_id')) {
            return redirect('login');
        }

        $huifu_id = $request->huifu_id;
        $tie_id = $request->tie_id;
        $bankuai_id = $request->bankuai_id;
        $content = $request->content;

        if ($content == NULL || $content == "") {
            return back()->withErrors('回复内容不能为空!!!', 'contentMessage')->withInput();
        }

        if (mb_strlen($content) > 1000) {
            return back()->withErrors('回复内容长度不能大于1000', 'contentMessage')->withInput();
        }

        $HuiUserRow = DB::table('huifu')->where('huifu_id', $huifu_id)->first();
        if (!$HuiUserRow) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        if (session('user_id') != $HuiUserRow->huifu_user) {
            echo "少年 不要乱搞<br/> <a href= '/'> 滚! ! ! !</a>";
            die;
        }

        DB::table('huifu')->where('huifu_id', $huifu_id)->update([
                'content' => $content,
                'update_time' => now()
            ]);

        return redirect('tiezi/' . $bankuai_id . '/' . $tie_id);
    }

    //登录
    public function login()
    {
        //如果已经登录了，则跳转至首页
        if (session('user_id')) {
            return redirect('/');
        }

        $currentUrl = url()->current();
        $previousUrl = url()->previous();
        if ($currentUrl != $previousUrl) {
            if (strpos($previousUrl, 'register')) {
                $url = "";
            } else {
                $url = $previousUrl;
            }
        } else {
            $url = "";
        }
        return view('login', ['url' => $url]);
    }

    //校验登录
    public function doLogin(Request $request)
    {
        $uName = $request->uName;
        $uPass = $request->uPass;
        $isAutoLogin = $request->isAutoLogin;
        $url = $request->url;

        if ($uName == NULL || $uName == "") {
            return back()->withErrors('用户名不能为空!!!', 'uNameMessage')->withInput();
        }


        if ($uPass == NULL || $uPass == "") {
            return back()->withErrors('密码不能为空!!!', 'uPassMessage')->withInput();
        }

        $userRow = DB::table('user')->where('user_name', $uName)->where('user_password', $uPass)->first();

        if ($userRow) {
            if ($isAutoLogin) {
                cookie('autoLoginFlag', 1, 3600);
                cookie('uName', $uName, 3600);
                cookie('uPass', $uPass, 3600);
            }
            session(['user' => $userRow, 'user_id' => $userRow->huifu_user]);
            if ($url) {
                return redirect($url);
            } else {
                return redirect('index');
            }
        } else {
            return back()->withErrors('用户名或密码错误!!!', 'login')->withInput();
        }
    }

    //退出
    public function doLogOut()
    {
        // 清除session值
        session(['user' => '', 'user_id' => '']);
        return redirect('login');
    }

    //注册页面
    public function register()
    {
        return view('register', ['gender' => 1]);
    }

    //校验注册
    public function doRegister(Request $request)
    {
        $uName = $request->uName;
        $uPass = $request->uPass;
        $uPass1 = $request->uPass1;
        $gender = $request->gender;
        $head = $request->head;

        if ($uName == NULL || $uName == "") {
            return back()->withErrors('用户名不能为空!!!', 'uNameMessage')->withInput();
        }

        if ($uPass == NULL || $uPass == "") {
            return back()->withErrors('密码不能为空!!!', 'uPassMessage')->withInput();
        }

        if (strlen($uPass) < 6) {
            return back()->withErrors('密码长度不能少于6个字符!!!', 'uPassMessage')->withInput();
        }

        if ($uPass != $uPass1) {
            return back()->withErrors('两次密码 不一致!!!', 'uPass1Message')->withInput();
        }

        $userRow = DB::table('user')->where('user_name', $uName)->first();

        if ($userRow) {
            return back()->withErrors('用户名已经存在', 'uNameMessage')->withInput();
        } else {
            DB::table('user')->insert([
                'user_name' => $uName,
                'user_password' => $uPass,
                'user_gender' => $gender,
                'headimage' => $head,
                'create_time' => now()
            ]);

            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function buy(Request $request, $id)
    {
        // Check is login
        if (!session('food_users')) {
            return redirect('login');
        }

        $dishes = Dishes::find($id);
        $users = Users::find(session("user_id"));

        $orders = new Orders;
        $orders->restaurant_id = $dishes->restaurant_id;
        $orders->dish_name = $dishes->dish_name;
        $orders->price = $dishes->price;
        $orders->consumer_id = session("user_id");
        $orders->consumer_name = $users->name;
        $orders->telphone = $users->telphone;
        $orders->address = $users->address;
        $orders->save();

        return redirect('users-orders');
    }

    /**
     * Show approved restaurant information.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        // Check is login
        if (!session('food_users')) {
            return redirect('login');
        }

        $list = Orders::leftJoin('restaurant', 'orders.restaurant_id', '=', 'restaurant.restaurant_id')
            ->where('consumer_id', session("user_id"))
            ->orderBy('orders.created_at', 'desc')
            ->select('orders.*', 'restaurant.restaurant_name')
            ->paginate(5);
        return view('orders', ['list' => $list]);
    }
}
