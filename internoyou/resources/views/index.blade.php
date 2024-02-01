@extends('common')
@section('title')
    欢迎访问斑马学员论坛
@endsection
@section('content')
    <DIV><!--      主体        -->
        <DIV class="t">
            <TABLE cellSpacing="0" cellPadding="0" width="100%">
                <TBODY>
                <TR class="tr2" align="middle">
                    <TD colSpan=2>Field & Position</TD>
                    <TD style="WIDTH: 5%">Amount</TD>
                    <TD style="WIDTH: 25%">Newest</TD>
                </TR><!--       主版块       -->
                @foreach ($bankuai as $dabankuai)
                    <TR class=tr3>
                        <TD colSpan=4> {{ $dabankuai->bankuai_name }} </TD>
                    </TR>
                    <!--       子版块       -->
                    @foreach ($dabankuai->zbk as $zbk)
                        <TR class=tr3>
                            <TD width="5%">&nbsp;</TD>
                            <TH align=left><IMG src="{{ asset('images/board.gif') }}">
                                <A href="{{ url('list', $zbk->bankuai_id) }}">{{ $zbk->bankuai_name }}</A>
                            </TH>
                            <TD align=middle>{{ $zbk->count }}</TD>
                            @if($zbk->count == 0)
                                <TH>
                                    There are no posts in this section yet
                                </TH>
                            @else
                                <TH>
                                    <SPAN><A href="#">{{ $zbk->lastTieziTitle }}</A></SPAN>
                                    <BR>
                                    <SPAN> {{ $zbk->lastTieziUserName }} </SPAN>
                                    <SPAN class=gray>[ {{ $zbk->lastTieziCreateTime }} ] </SPAN>
                                </TH>
                            @endif
                        </TR>
                    @endforeach
                @endforeach
                </TBODY>
            </TABLE>
        </DIV>
    </DIV>
@endsection
