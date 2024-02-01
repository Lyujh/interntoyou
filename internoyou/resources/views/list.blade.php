@extends('common')
@section('title')
    斑马学员论坛--帖子列表
@endsection
@section('content')
    <DIV>
        <!--      导航        --><BR>
        <DIV>&gt;&gt; <B> <A href="/">Front page</A></B>&gt;&gt; <B> <A
                    href="{{ url('list', $bankuai->bankuai_id) }}">{{ $bankuai->bankuai_name }}</A> </B>
        </DIV>
        <BR><!--      新帖        -->
        <DIV>
            <A href="{{ url('fatie', $bankuai->bankuai_id) }}">
                <IMG border=0 src="{{ asset('images/post.gif') }}">
            </A>
        </DIV>
        <DIV class="t">
            <TABLE cellSpacing=0 cellPadding=0 width="100%">
                <TBODY>
                <TR>
                    <TH style="WIDTH: 100%" class=h colSpan=4><SPAN>&nbsp;</SPAN></TH>
                </TR><!--       表 头           -->
                <TR class=tr2>
                    <TD>&nbsp;</TD>
                    <TD style="WIDTH: 80%" align=middle>Content</TD>
                    <TD style="WIDTH: 10%" align=middle>User</TD>
                    <TD style="WIDTH: 10%" align=middle>Reply</TD>
                </TR>
                <!--         主 题 列 表        -->
                @foreach ($list as $t)
                    <TR class=tr3>
                        <TD><IMG border=0 src="{{ asset('images/topic.gif') }}"></TD>
                        <TD style="FONT-SIZE: 15px">
                            <A href="{{ url('tiezi', [$bankuai->bankuai_id, $t->tie_id]) }}">
                                {{ $t->title }}
                            </A>
                        </TD>
                        <TD align=middle>{{ $t->user_name }}</TD>
                        <TD align=middle>{{ $t->counts }}</TD>
                    </TR>
                @endforeach
                </TBODY>
            </TABLE>
        </DIV>
        <!--            翻 页          -->
        <DIV class="pagination">
            {{ $list->links() }}
        </DIV>
    </DIV>
@endsection
