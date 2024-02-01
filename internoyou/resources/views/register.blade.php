@extends('common')
@section('title')
    斑马学员论坛--注册
@endsection
@section('content')
    <SCRIPT language=javascript>
        function check() {
            if (document.regForm.uName.value == "") {
                alert("Username can not be empty");
                return false;
            }
            if (document.regForm.uPass.value == "") {
                alert("Password can not be empty");
                return false;
            }
            if (document.regForm.uPass.value != document.regForm.uPass1.value) {
                alert("The password is different");
                return false;
            }
            if (document.regForm.uPass.value.length < 6) {
                alert("Password cannot be less than 6 characters");
                return false;
            }
        }
    </SCRIPT>
    <BR>
    <!--      导航        -->
    <DIV>&gt;&gt;<B><A href="/">Front page</A></B>
    </DIV><!--      用户注册表单        -->
    <DIV style="MARGIN-TOP: 15px" class="t" align="center">
        @if(count($errors->message) > 0)
            <p>{{ $errors->message->first() }}</p>
        @endif
        <FORM onsubmit="return check()" method="post" name="regForm" action="{{ url('doRegister') }}">
            @csrf
            <BR>Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT class="input" tabIndex="1" maxLength="20" size="40" name="uName"
                                              type="text" value="{{ old ('uName') }}">
            @if(count($errors->uNameMessage) > 0)
                <span>{{ $errors->uNameMessage->first() }}</span>
            @endif
            <BR>Password &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT class="input" tabIndex="2" maxLength="20" size="40"
                                                        type="password" name="uPass" value="{{ old ('uPass') }}">
            @if(count($errors->uPassMessage) > 0)
                <span>{{ $errors->uPassMessage->first() }}</span>
            @endif
            <BR>Repeat password&nbsp;<INPUT class="input" tabIndex="3" maxLength="20" size="40" type="password" name="uPass1"
                                   value="{{ old ('uPass1') }}">
            @if(count($errors->uPass1Message) > 0)
                <span>{{ $errors->uPass1Message->first() }}</span>
            @endif
            <BR>Gender &nbsp; Female <INPUT value="1" type="radio"
                                   name="gender" {{ old ('gender') == '1' ? 'checked' : ($gender == 1 ? 'checked' : '') }}>
            Male <INPUT value="2" type="radio" name="gender" {{ old ('gender') == '2' ? 'checked' : '' }}>

            <BR>Please select an avatar<BR>
            @for ($i = 1; $i <= 15; $i++)
                <IMG src="images/{{ $i }}.gif">
                <INPUT value="{{ $i }}.gif" type="radio"
                       name="head" {{ old ('head') == $i ? 'checked' : ($i == 1 ? 'checked' : '') }}>
                @if ($i % 5 == 0)
                    <br />
                @endif
            @endfor
            <BR><INPUT class="btn" tabIndex="4" value="Register" type="submit">
        </FORM>
    </DIV>
@endsection
