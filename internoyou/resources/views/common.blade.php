<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<HEAD>
    <TITLE>@yield('title')</TITLE>
    <META content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <META name=GENERATOR content="MSHTML 8.00.6001.18783">
</HEAD>
<BODY>
<DIV>
    <IMG src="{{ asset('images/logo.png') }}" width="123px;" height="45px;">
</DIV>
@if ( session('user_id') )
    <DIV class=h>
        Hello {{ session('user')->user_name }}&nbsp;| &nbsp; <A href="{{ url('doLogOut') }}">Sign out</A>
    </DIV>
@else
    <DIV class=h>
        You haven't &nbsp<A href="{{ url('login') }}">Log in</A> &nbsp;| &nbsp; <A href="{{ url('register') }}">Register</A>
    </DIV>
@endif

@yield('content')
<BR>
<CENTER class=gray>
    http://interntoyou.com/
</CENTER>
</BODY>
</HTML>
