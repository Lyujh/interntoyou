@extends('common')
@section('title')
    斑马学员论坛--登录
@endsection
@section('content')
    <SCRIPT language=javascript>
        function check() {
            if (document.loginForm.uName.value == "") {
                alert("Username can not be empty");
                return false;
            }
            if (document.loginForm.uPass.value == "") {
                alert("Password can not be empty");
                return false;
            }
        }
    </SCRIPT>
<BR>
    <DIV>&gt;&gt;<B><A href="/">Front page</A></B></DIV>
    <!--      用户登录表单        -->
    <DIV style="MARGIN-TOP: 15px" class="t" align="center">
        <FORM onsubmit="return check()" method=post name="loginForm" action="{{ url('doLogin') }}"><BR>
            @csrf
            @if(count($errors->login) > 0)
                <p>{{ $errors->login->first() }}</p>
            @endif
            <input type="hidden" name="url" value="{{ $url }}">
            Username <INPUT class="input" tabIndex="1" maxLength="20" size="40" type="text" name="uName">
            @if(count($errors->uNameMessage) > 0)
                <span>{{ $errors->uNameMessage->first() }}</span>
            @endif
            <BR>Password&nbsp; <INPUT class="input" tabIndex="2" maxLength="20" size="40" type="password" name="uPass">
            @if(count($errors->uPassMessage) > 0)
                <span>{{ $errors->uPassMessage->first() }}</span>
            @endif
            <BR>
            Remember me &nbsp; <INPUT class="input" type="checkbox" name="isAutoLogin">
            <BR>
            <INPUT class=btn tabIndex=6 value="Log in" type=submit></FORM>
    </DIV>

@endsection
