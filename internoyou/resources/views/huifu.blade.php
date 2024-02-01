@extends('common')
@section('title')
    斑马学员论坛--回复帖子
@endsection
@section('content')
<DIV><BR><!--      导航        -->
    <DIV>&gt;&gt;<B>
            <A href="/">Front page</A></B>&gt;&gt; <B>
            <A href="{{ url('list', $bankuai->bankuai_id) }}">{{ $bankuai->bankuai_name }}</A> </B>

    </DIV><BR>
    <DIV>
        <FORM onsubmit="return check()" method="post" name="postForm" action="{{ url('doHuifu') }}">
            @csrf
            <DIV class="t">
                <TABLE cellSpacing="0" cellPadding="0" align="center">
                    <TBODY>
                    <TR>
                        <TD class="h" colSpan="3"><B>Reply</B></TD>
                    </TR>
                    <INPUT type="hidden" name="tie_id" value="{{ $tie_id }}">
                    <INPUT type="hidden" name="bankuai_id" value="{{ $bankuai->bankuai_id }}">

                    <TR class="tr3">
                        <TH vAlign="top">
                            <DIV><B>Content</B></DIV>
                        </TH>
                        <TH colSpan="2">
                            <DIV>
                                <SPAN>
                                    <TEXTAREA style="WIDTH: 500px" tabIndex="2" rows="20" cols="90" name="content"></TEXTAREA>
                                    @if(count($errors->contentMessage) > 0)
                                        <p>{{ $errors->contentMessage->first() }}</p>
                                    @endif
                                </SPAN>
                            </DIV>
                            (Cannot be greater than:<FONT color=blue>1000</FONT>Word)
                        </TH>
                    </TR>
                    </TBODY>
                </TABLE>
            </DIV>
            <DIV style="TEXT-ALIGN: center; MARGIN: 15px 0px">
                <INPUT class="btn" tabIndex="3" value="Submit" type="submit">
                <INPUT class="btn" tabIndex="4" value="Reset" type="reset">
            </DIV>
        </FORM>
    </DIV>
</DIV>
    <SCRIPT type=text/javascript>
        function check() {
            if (document.postForm.content.value == "") {
                alert("The content can not be blank");
                return false;
            }
            if (document.postForm.content.value.length > 1000) {
                alert("Content length cannot be greater than 1000");
                return false;
            }
        }
    </SCRIPT>

@endsection
