@extends('common')
@section('title')
    斑马学员论坛--看贴
@endsection
@section('content')
<DIV>
    <!--      主体        -->
    <DIV><BR><!--      导航        -->

        <DIV>&gt;&gt; <B> <A href="/">Front page</A></B>&gt;&gt; <B> <A
                    href="{{ url('list', $bankuai->bankuai_id) }}">{{ $bankuai->bankuai_name }}</A> </B>
        </DIV>
        <BR>
        <!--      回复、新帖        -->
        <DIV>
            <A href="{{ url('huifu', [$bankuai->bankuai_id, $tiezi->tie_id]) }}">
                <IMG border="0" src="{{ asset('images/reply.gif') }}">
            </A>
            <A href="{{ url('fatie', $bankuai->bankuai_id) }}">
                <IMG border="0" src="{{ asset('images/post.gif') }}">
            </A>
        </DIV>

        <!--      本页主题的标题        -->
        <DIV>
            <TABLE cellSpacing="0" cellPadding="0" width="100%">
                <TBODY>
                <TR>
                    <TH class=h>Topic of this page: {{ $tiezi->title }} </TH>
                </TR>
                <TR class=tr2>
                    <TD>&nbsp;</TD>
                </TR>
                </TBODY>
            </TABLE>
        </DIV>
        <!--      主题        -->
        <DIV class="t">
            <TABLE style="BORDER-TOP-WIDTH: 0px; TABLE-LAYOUT: fixed" cellSpacing="0" cellPadding="0" width="100%">
                <TBODY>
                <TR class="tr1">
                    <TH style="WIDTH: 20%">
                        <B>{{ $tiezi->user_name }}</B><BR><IMG
                            src="{{ asset('images/' . $tiezi->headimage) }}"><BR>{{ $tiezi->uctime }}<BR></TH>
                    <TH>
                        <H4>{{ $tiezi->title }}</H4>
                        <DIV>
                            <PRE>{{ $tiezi->content }}</PRE>
                        </DIV>
                        <DIV class="tipad gray">Publish：[{{ $tiezi->create_time }}] &nbsp; latest update:[{{ $tiezi->update_time }}]
                            @if (session('user_id') && $tiezi->huifu_user == session('user_id'))
                                <A href="javascript:deleteTieziConfirm({{ $tiezi->tie_id }}) ">[Delete]</A>
                                <A href="{{ url('fatieEdit', [$bankuai->bankuai_id, $tiezi->tie_id]) }}">[Modify]</A>
                            @endif

                        </DIV>
                    </TH>
                </TR>
                </TBODY>
            </TABLE>
        </DIV>

        <!--      回复        -->
        @foreach ($list as $h)
            <DIV class=t>
                <TABLE style="BORDER-TOP-WIDTH: 0px; TABLE-LAYOUT: fixed" cellSpacing="0" cellPadding="0" width="100%">
                    <TBODY>
                    <TR class="tr1">
                        <TH style="WIDTH: 20%"><B>{{ $h->user_name }}</B><BR><BR>
                            <IMG src="{{ asset('images/'.$h->headimage) }}"><BR>{{ $h->uctime }}<BR></TH>
                        <TH>
                            <H4>re：</H4>
                            <DIV>
                                <PRE>{{ $h->content }}</PRE>
                            </DIV>
                            <DIV class="tipad gray">Publish：[{{ $h->create_time }}] &nbsp;  latest update:[{{ $h->update_time }}>]
                                @if (session('user_id') && $h->huifu_user == session('user_id'))
                                    <A href=" javascript:deleteConfirm({{ $h->huifu_id }})">[Delete]</A>
                                    <A href="{{ url('huifuEdit', [$h->huifu_id, $bankuai->bankuai_id, $tiezi->tie_id]) }}">[Modify]</A>
                                @endif
                            </DIV>
                        </TH>
                    </TR>
                    </TBODY>
                </TABLE>
            </DIV>
        @endforeach

        <DIV class="pagination">
            {{ $list->links() }}
        </DIV>
    </DIV>
</DIV>
    <SCRIPT type=text/javascript>
        function deleteConfirm(huifu_id) {
            if (confirm("Are you sure you want to delete this reply?")) {
                window.location.href = "{{ url('doHuifuDelete') }}/" + huifu_id + "/{{ $bankuai->bankuai_id }}/{{ $tiezi->tie_id }}";
            }
        }
    </SCRIPT>


    <SCRIPT type=text/javascript>
        function deleteTieziConfirm($tie_id) {
            if (confirm("Are you sure to delete")) {
                window.location.href = "{{ url('doTieziDelete', [$bankuai->bankuai_id, $tiezi->tie_id]) }}";
            }
        }
    </SCRIPT>
@endsection
